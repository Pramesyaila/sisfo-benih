@extends('layouts.admin')

@section('title', 'Laporan Stok')

@section('content')
<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end bg-white p-4 rounded-xl border border-gray-100 print:hidden">
    <div><label class="block text-xs text-gray-500 mb-1">Dari</label><input type="date" name="from" value="{{ $from }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <div><label class="block text-xs text-gray-500 mb-1">Sampai</label><input type="date" name="to" value="{{ $to }}" class="rounded-md border-gray-300 px-3 py-2 border text-sm"></div>
    <button class="bg-primary text-white px-4 py-2 rounded-md text-sm hover:bg-primary-dark">Tampilkan</button>
    <button type="button" onclick="window.print()" class="bg-accent text-primary-dark px-4 py-2 rounded-md text-sm font-semibold hover:brightness-95">🖨️ Cetak</button>
</form>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="bg-white rounded-xl p-4 border"><p class="text-xs text-gray-500">Total Stok Masuk</p><p class="text-xl font-bold text-primary-dark">{{ $totalMasuk }}</p></div>
    <div class="bg-white rounded-xl p-4 border"><p class="text-xs text-gray-500">Total Stok Keluar</p><p class="text-xl font-bold text-primary-dark">{{ $totalKeluar }}</p></div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-base text-gray-500 text-left"><tr><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Produk</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Stok Akhir</th></tr></thead>
        <tbody class="divide-y">
            @forelse($transactions as $t)
            <tr>
                <td class="px-4 py-3">{{ $t->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">{{ $t->product->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ ucfirst($t->type) }}</td>
                <td class="px-4 py-3">{{ $t->qty }}</td>
                <td class="px-4 py-3">{{ $t->stock_after }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
