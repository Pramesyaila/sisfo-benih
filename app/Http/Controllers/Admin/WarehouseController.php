<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    // Daftar pesanan yang siap diserahkan (sudah faktur terbit)
    public function index()
    {
        $orders = Order::with(['user', 'items', 'invoice'])
            ->where('status', 'faktur_terbit')
            ->latest()
            ->paginate(10);

        return view('admin.orders.warehouse', compact('orders'));
    }

    // Petugas gudang menyerahkan benih -> stok keluar otomatis tercatat
    public function release(Order $order)
    {
        if ($order->status !== 'faktur_terbit') {
            return back()->with('error', 'Pesanan belum memiliki faktur / sudah diserahkan.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if (! $item->product) {
                    continue;
                }

                $before = $item->product->stock;
                $after = max(0, $before - $item->qty);

                $item->product->update(['stock' => $after]);

                StockTransaction::create([
                    'product_id' => $item->product_id,
                    'type' => 'keluar',
                    'qty' => $item->qty,
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'note' => "Distribusi pesanan {$order->order_number}",
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]);
            }

            $order->update(['status' => 'siap_diambil']);
        });

        return back()->with('success', 'Benih berhasil disiapkan, stok otomatis berkurang. Pesanan siap diambil konsumen.');
    }
}
