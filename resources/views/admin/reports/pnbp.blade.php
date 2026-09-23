@extends('layouts.admin')

@section('title', 'Laporan PNBP')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end bg-white p-4 rounded-xl border border-gray-100 print:hidden">
    <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <select name="status" class="rounded-md border-gray-300 px-3 py-2 border text-sm">
        <option value="">Semua Status</option>
        @foreach(['belum_dibayar','menunggu_verifikasi','lunas','ditolak'] as $s)
            <option value="{{ $s }}" @selected(request('status')===$s)>{{ \App\Models\PnbpBill::statusLabel($s) }}</option>
        @endforeach
    </select>
    <button class="bg-primary text-white px-4 py-2 rounded-md text-sm hover:bg-primary-dark">Tampilkan</button>
    <button type="button" onclick="window.print()" class="bg-accent text-primary-dark px-4 py-2 rounded-md text-sm font-semibold hover:brightness-95">🖨️ Cetak</button>
</form>

<div class="bg-white rounded-xl p-4 border mb-4 w-fit">
    <p class="text-xs text-gray-500">Total PNBP Lunas</p>
    <p class="text-xl font-bold text-primary-dark">Rp{{ number_format($totalLunas,0,',','.') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left"><tr><th class="px-4 py-3">No. Tagihan</th><th class="px-4 py-3">Transaksi</th><th class="px-4 py-3">Nominal</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Tanggal Bayar</th></tr></thead>
        <tbody class="divide-y">
            @forelse($bills as $bill)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $bill->bill_number }}</td>
                <td class="px-4 py-3">{{ $bill->order->order_number }}</td>
                <td class="px-4 py-3">{{ $bill->formattedAmount() }}</td>
                <td class="px-4 py-3">{{ \App\Models\PnbpBill::statusLabel($bill->status) }}</td>
                <td class="px-4 py-3">{{ optional($bill->paid_at)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
