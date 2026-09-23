@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<h1 class="text-xl font-bold text-primary-dark mb-4">Pesanan Saya</h1>

@if($orders->isEmpty())
    <div class="bg-white rounded-xl p-10 text-center text-gray-500 shadow-sm">
        Anda belum memiliki pesanan.
        <div class="mt-3">
            <a href="{{ route('catalog.index') }}" class="text-primary-dark font-semibold hover:underline">Mulai belanja &rarr;</a>
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">No. Pesanan</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $order->formattedTotal() }}</td>
                        <td class="px-4 py-3"><span class="badge {{ $order->statusBadgeColor() }}">{{ \App\Models\Order::statusLabel($order->status) }}</span></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('orders.show', $order) }}" class="text-primary-dark font-semibold hover:underline">Detail &rarr;</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
@endif
@endsection
