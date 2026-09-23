@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end bg-white p-4 rounded-xl border border-gray-100 print:hidden">
    <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <button class="bg-primary text-white px-4 py-2 rounded-md text-sm hover:bg-primary-dark">Tampilkan</button>
    <button type="button" onclick="window.print()" class="bg-accent text-primary-dark px-4 py-2 rounded-md text-sm font-semibold hover:brightness-95">🖨️ Cetak</button>
</form>

<div class="bg-white rounded-xl p-4 border mb-4 w-fit">
    <p class="text-xs text-gray-500">Total Penjualan (lunas ke atas)</p>
    <p class="text-xl font-bold text-primary-dark">Rp{{ number_format($totalPenjualan,0,',','.') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left"><tr><th class="px-4 py-3">No. Transaksi</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Status</th></tr></thead>
        <tbody class="divide-y">
            @forelse($orders as $order)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                <td class="px-4 py-3">{{ $order->user->name }}</td>
                <td class="px-4 py-3">{{ $order->items->pluck('product_name')->join(', ') }}</td>
                <td class="px-4 py-3">{{ $order->formattedTotal() }}</td>
                <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">{{ \App\Models\Order::statusLabel($order->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
