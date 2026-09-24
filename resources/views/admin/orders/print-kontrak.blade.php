@extends('layouts.print')
@section('title', 'Surat Perjanjian - ' . $order->contract->contract_number)

@section('content')
<h2 class="text-center font-bold text-base uppercase mb-1">Surat Perjanjian Jual Beli Benih/Bibit</h2>
<p class="text-center mb-4">Nomor: {{ $order->contract->contract_number }}</p>

<p>Pada hari ini, {{ $order->contract->created_at->translatedFormat('l, d F Y') }}, telah disepakati perjanjian jual beli benih/bibit berdasarkan permohonan nomor {{ $order->order_number }}, dengan rincian sebagai berikut:</p>

<table class="mt-3 w-full">
    <tr><td class="w-56 align-top py-0.5">Nama</td><td class="align-top py-0.5">: {{ $order->contract->nama }}</td></tr>
    <tr><td class="align-top py-0.5">Nomor KTP</td><td class="align-top py-0.5">: {{ $order->contract->nik ?? '-' }}</td></tr>
    <tr><td class="align-top py-0.5">Domisili</td><td class="align-top py-0.5">: {{ $order->contract->domisili ?? '-' }}</td></tr>
    <tr><td class="align-top py-0.5">Alamat</td><td class="align-top py-0.5">: {{ $order->contract->alamat ?? '-' }}</td></tr>
    <tr><td class="align-top py-0.5">No. HP/WA</td><td class="align-top py-0.5">: {{ $order->user->phone ?? '-' }}</td></tr>
</table>

<p class="mt-4">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>, telah menyepakati jual beli benih/bibit dengan rincian sebagai berikut:</p>

<table class="mt-3 w-full border-collapse border border-black text-xs">
    <thead>
        <tr class="text-center">
            <th class="border border-black p-1 w-8">No.</th>
            <th class="border border-black p-1">Jenis Produk</th>
            <th class="border border-black p-1">Jumlah</th>
            <th class="border border-black p-1">Satuan</th>
            <th class="border border-black p-1">Harga (Rp)</th>
            <th class="border border-black p-1">Jumlah Harga (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $i => $item)
        <tr class="text-center">
            <td class="border border-black p-1">{{ $i + 1 }}</td>
            <td class="border border-black p-1 text-left">{{ $item->product_name }}</td>
            <td class="border border-black p-1">{{ $item->qty }}</td>
            <td class="border border-black p-1">{{ $item->packaging ?? '-' }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item->price,0,',','.') }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="font-bold">
            <td colspan="5" class="border border-black p-1 text-right">Total</td>
            <td class="border border-black p-1 text-right">{{ number_format($order->total,0,',','.') }}</td>
        </tr>
    </tbody>
</table>

<table class="mt-4 w-full">
    <tr><td class="w-64 align-top py-0.5">Tujuan penggunaan</td><td class="align-top py-0.5">: {{ $order->notes }}</td></tr>
    <tr><td class="align-top py-0.5">Tgl rencana pengambilan/pengiriman</td><td class="align-top py-0.5">: {{ optional($order->pickup_date)->format('d F Y') ?? '-' }}</td></tr>
    <tr><td class="align-top py-0.5">Lokasi pengambilan/pengiriman</td><td class="align-top py-0.5">: {{ $order->pickupLocationLabel() }}</td></tr>
</table>

<p class="mt-4">Kedua belah pihak sepakat bahwa penyiapan dan penyerahan benih/bibit akan diproses setelah pembayaran dilakukan melalui kode billing PNBP sesuai ketentuan yang berlaku.</p>

<div class="flex justify-between mt-12">
    <div class="text-center">
        <p>Pihak Pertama,</p>
        <div class="h-20"></div>
        <p class="font-semibold">( .......................... )</p>
        <p class="text-xs">Petugas Layanan</p>
    </div>
    <div class="text-center">
        <p>Pihak Kedua,</p>
        <div class="h-20"></div>
        <p class="font-semibold">{{ $order->contract->nama }}</p>
        <p class="text-xs">Pemohon</p>
    </div>
</div>
@endsection