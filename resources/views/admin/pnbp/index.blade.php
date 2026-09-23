@extends('layouts.admin')

@section('title', 'Tagihan PNBP')

@section('content')
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
