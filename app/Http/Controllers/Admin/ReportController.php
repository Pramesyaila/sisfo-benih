<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PnbpBill;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $transactions = StockTransaction::with('product')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->product_id))
            ->latest()
            ->get();

        $totalMasuk = $transactions->where('type', 'masuk')->sum('qty');
        $totalKeluar = $transactions->where('type', 'keluar')->sum('qty');

        return view('admin.reports.stock', compact('transactions', 'from', 'to', 'totalMasuk', 'totalKeluar'));
    }

    public function sales(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $orders = Order::with(['user', 'items'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        $totalPenjualan = $orders->whereIn('status', ['lunas', 'faktur_terbit', 'siap_diambil', 'selesai'])->sum('total');

        return view('admin.reports.sales', compact('orders', 'from', 'to', 'totalPenjualan'));
    }

    public function distribution(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $transactions = StockTransaction::with(['product', 'order.user'])
            ->where('type', 'keluar')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        return view('admin.reports.distribution', compact('transactions', 'from', 'to'));
    }

    public function pnbp(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $bills = PnbpBill::with('order.user')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        $totalLunas = $bills->where('status', 'lunas')->sum('amount');

        return view('admin.reports.pnbp', compact('bills', 'from', 'to', 'totalLunas'));
    }
}
