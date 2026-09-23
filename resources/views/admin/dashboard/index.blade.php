@extends('layouts.admin')

@section('title', 'Dashboard - ' . $user->roleLabel())

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach($stats as $label => $value)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold text-primary-dark">{{ $value }}</p>
        </div>
    @endforeach
</div>

@if(isset($recentOrders))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-5 py-3 font-semibold border-b">Pesanan Terbaru</div>
        <table class="w-full text-sm">
            <thead class="bg-base text-gray-500 text-left">
                <tr><th class="px-4 py-3">No. Pesanan</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Status</th><th></th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recentOrders as $order)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">{{ $order->user->name }}</td>
                    <td class="px-4 py-3">{{ $order->formattedTotal() }}</td>
                    <td class="px-4 py-3"><span class="badge {{ $order->statusBadgeColor() }}">{{ \App\Models\Order::statusLabel($order->status) }}</span></td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-primary-dark hover:underline">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

@if(isset($recentBills))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-5 py-3 font-semibold border-b">Tagihan PNBP Terbaru</div>
        <table class="w-full text-sm">
            <thead class="bg-base text-gray-500 text-left">
                <tr><th class="px-4 py-3">No. Tagihan</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Status</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recentBills as $bill)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $bill->bill_number }}</td>
                    <td class="px-4 py-3">{{ $bill->order->user->name }}</td>
                    <td class="px-4 py-3">{{ $bill->formattedAmount() }}</td>
                    <td class="px-4 py-3">{{ \App\Models\PnbpBill::statusLabel($bill->status) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada tagihan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

@if(isset($lowStockProducts))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-5 py-3 font-semibold border-b">Produk Stok Menipis</div>
        <table class="w-full text-sm">
            <thead class="bg-base text-gray-500 text-left">
                <tr><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Stok</th><th class="px-4 py-3">Batas Minimum</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse($lowStockProducts as $p)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-accent font-semibold">{{ $p->stock }}</td>
                    <td class="px-4 py-3">{{ $p->min_stock }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Semua stok aman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif
@endsection
