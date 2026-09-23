@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1 class="text-xl font-bold text-primary-dark mb-4">Checkout Pesanan</h1>

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 divide-y">
        @foreach($cart as $row)
            <div class="p-4 flex items-center gap-4">
                <div class="h-14 w-14 bg-accent-light rounded-md flex items-center justify-center text-xl flex-shrink-0">🌱</div>
                <div class="flex-1">
                    <p class="font-semibold">{{ $row['product']->name }}</p>
                    <p class="text-xs text-gray-500">{{ $row['qty'] }} {{ $row['product']->packaging_unit }} &times; {{ $row['product']->formattedPrice() }}</p>
                </div>
                <p class="font-semibold text-primary-dark">Rp{{ number_format($row['subtotal'],0,',','.') }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 h-fit">
        <h2 class="font-semibold mb-3">Ringkasan</h2>
        <div class="flex justify-between text-sm mb-1">
            <span class="text-gray-500">Total item</span>
            <span>{{ $cart->sum('qty') }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg text-primary-dark border-t pt-2 mt-2">
            <span>Total</span>
            <span>Rp{{ number_format($total,0,',','.') }}</span>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="mt-4">
            @csrf
            <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
            <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border text-sm"></textarea>
            <button class="w-full mt-3 bg-accent hover:brightness-95 text-primary-dark font-bold px-6 py-3 rounded-md">
                Buat Pesanan
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-3">
            Setelah pesanan dibuat, petugas layanan akan memproses administrasi, kontrak, dan tagihan PNBP.
        </p>
    </div>
</div>
@endsection
