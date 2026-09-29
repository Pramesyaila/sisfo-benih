@extends('layouts.admin')

@section('title', 'Tagihan PNBP')

@section('content')

@if($waitingForBill->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto mb-6">
    <div class="px-4 py-3 border-b bg-base font-semibold text-sm">Pesanan Menunggu Tagihan ({{ $waitingForBill->count() }})</div>
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left">
            <tr><th class="px-4 py-3">No. Pesanan</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">No. Kontrak</th><th class="px-4 py-3">Total</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @foreach($waitingForBill as $order)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                <td class="px-4 py-3">{{ $order->user->name }}</td>
                <td class="px-4 py-3">{{ $order->contract->contract_number }}</td>
                <td class="px-4 py-3">{{ $order->formattedTotal() }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.orders.show', $order) }}" class="bg-accent text-primary-dark px-3 py-1.5 rounded-md text-xs font-semibold hover:brightness-95 inline-block">Isi Tagihan →</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<form method="GET" class="mb-4 flex gap-2">
    <select name="status" class="rounded-md border-gray-300 px-3 py-2 border text-sm">
        <option value="">Semua Status</option>
        @foreach(['belum_dibayar','menunggu_verifikasi','lunas','ditolak'] as $s)
            <option value="{{ $s }}" @selected(request('status')===$s)>{{ \App\Models\PnbpBill::statusLabel($s) }}</option>
        @endforeach
    </select>
    <button class="bg-base border border-gray-200 px-3 py-2 rounded-md text-sm hover:border-primary">Filter</button>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left">
            <tr><th class="px-4 py-3">No. Tagihan</th><th class="px-4 py-3">Pesanan</th><th class="px-4 py-3">Konsumen</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Jatuh Tempo</th><th class="px-4 py-3">Status</th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bills as $bill)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $bill->bill_number }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $bill->order) }}" class="text-primary-dark hover:underline">{{ $bill->order->order_number }}</a></td>
                <td class="px-4 py-3">{{ $bill->order->user->name }}</td>
                <td class="px-4 py-3">{{ $bill->formattedAmount() }}</td>
                <td class="px-4 py-3">{{ optional($bill->due_date)->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><span class="badge bg-accent-light text-primary-dark">{{ \App\Models\PnbpBill::statusLabel($bill->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada tagihan PNBP.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bills->links() }}</div>
@endsection