@extends('layouts.admin')

@section('title', 'Serah Terima Benih (Gudang)')

@section('content')
<p class="text-sm text-gray-500 mb-4">Pesanan berikut sudah memiliki faktur dan siap diserahkan ke konsumen. Klik "Serahkan" untuk mencatat stok keluar secara otomatis.</p>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left">
            <tr><th class="px-4 py-3">No. Pesanan</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">No. Faktur</th><th class="px-4 py-3">Total</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse($orders as $order)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                <td class="px-4 py-3">{{ $order->user->name }}</td>
                <td class="px-4 py-3">{{ optional($order->invoice)->invoice_number }}</td>
                <td class="px-4 py-3">{{ $order->formattedTotal() }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-dark hover:underline">Detail</a>
                    <form action="{{ route('admin.warehouse.release', $order) }}" method="POST" class="inline" onsubmit="return confirm('Serahkan benih dan kurangi stok?')">
                        @csrf
                        <button class="bg-primary text-white px-3 py-1.5 rounded-md text-xs hover:bg-primary-dark">Serahkan Benih</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada pesanan yang menunggu serah terima.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
