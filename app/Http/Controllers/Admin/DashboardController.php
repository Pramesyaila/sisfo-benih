<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PnbpBill;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
        ];

        if ($user->role === 'petugas_layanan') {
            $data['stats'] = [
                'Pesanan Baru' => Order::where('status', 'dipesan')->count(),
                'Diproses' => Order::where('status', 'diproses')->count(),
                'Menunggu Verifikasi' => Order::where('status', 'menunggu_verifikasi')->count(),
                'Menunggu Pembayaran' => Order::where('status', 'menunggu_pembayaran')->count(),
                'Siap Diambil' => Order::where('status', 'siap_diambil')->count(),
                'Selesai' => Order::where('status', 'selesai')->count(),
            ];
            $data['recentOrders'] = Order::with('user')->latest()->limit(8)->get();
        } elseif ($user->role === 'petugas_pnbp') {
            $data['stats'] = [
                'Tagihan Belum Dibayar' => PnbpBill::where('status', 'belum_dibayar')->count(),
                'Menunggu Verifikasi' => PnbpBill::where('status', 'menunggu_verifikasi')->count(),
                'Lunas' => PnbpBill::where('status', 'lunas')->count(),
                'Total PNBP Terkumpul' => 'Rp' . number_format(PnbpBill::where('status', 'lunas')->sum('amount'), 0, ',', '.'),
            ];
            $data['recentBills'] = PnbpBill::with('order.user')->latest()->limit(8)->get();
        } else { // manager_gudang
            $data['stats'] = [
                'Total Produk' => Product::count(),
                'Stok Tersedia' => Product::sum('stock'),
                'Stok Menipis' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
                'Stok Masuk Hari Ini' => StockTransaction::where('type', 'masuk')->whereDate('created_at', today())->sum('qty'),
                'Stok Keluar Hari Ini' => StockTransaction::where('type', 'keluar')->whereDate('created_at', today())->sum('qty'),
            ];
            $data['lowStockProducts'] = Product::whereColumn('stock', '<=', 'min_stock')->limit(8)->get();
        }

        return view('admin.dashboard.index', $data);
    }
}
