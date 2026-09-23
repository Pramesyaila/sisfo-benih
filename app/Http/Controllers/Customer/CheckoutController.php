<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = CartController::cartWithProducts($request);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang anda masih kosong.');
        }

        $total = $cart->sum('subtotal');

        return view('customer.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = CartController::cartWithProducts($request);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang anda masih kosong.');
        }

        // Cek ketersediaan stok sebelum pesanan dibuat
        foreach ($cart as $row) {
            if ($row['qty'] > $row['product']->stock) {
                return back()->with('error', "Stok {$row['product']->name} tidak mencukupi.");
            }
        }

        $order = DB::transaction(function () use ($cart, $request) {
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => Auth::id(),
                'status' => 'dipesan',
                'total' => $cart->sum('subtotal'),
                'notes' => $request->notes,
            ]);

            foreach ($cart as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product']->id,
                    'product_name' => $row['product']->name,
                    'packaging' => $row['product']->packagingLabel(),
                    'price' => $row['product']->price,
                    'qty' => $row['qty'],
                    'subtotal' => $row['subtotal'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan tunggu proses administrasi dari petugas.');
    }
}
