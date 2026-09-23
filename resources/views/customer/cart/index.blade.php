@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<h1 class="text-xl font-bold text-primary-dark mb-4">Keranjang Belanja</h1>

@if($cart->isEmpty())
    <div class="bg-white rounded-xl p-10 text-center text-gray-500 shadow-sm">
        Keranjang anda masih kosong.
        <div class="mt-3">
            <a href="{{ route('catalog.index') }}" class="text-primary-dark font-semibold hover:underline">Mulai belanja &rarr;</a>
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y">
        @foreach($cart as $row)
            <div class="p-4 flex items-center gap-4">
                <div class="h-16 w-16 bg-accent-light rounded-md flex items-center justify-center text-2xl flex-shrink-0">🌱</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate">{{ $row['product']->name }}</p>
                    <p class="text-xs text-gray-500">{{ $row['product']->packagingLabel() }} &middot; {{ $row['product']->formattedPrice() }}</p>
                </div>
                <form action="{{ route('cart.update', $row['product']) }}" method="POST" class="flex items-center gap-1">
                    @csrf @method('PATCH')
                    <input type="number" name="qty" value="{{ $row['qty'] }}" min="1" max="{{ $row['product']->stock }}"
                        class="w-16 rounded-md border-gray-300 focus:border-primary focus:ring-primary px-2 py-1 border text-sm">
                    <button class="text-xs bg-base border border-gray-200 px-2 py-1.5 rounded-md hover:border-primary">Update</button>
                </form>
                <p class="w-28 text-right font-semibold text-primary-dark">Rp{{ number_format($row['subtotal'],0,',','.') }}</p>
                <form action="{{ route('cart.remove', $row['product']) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Total belanja</p>
            <p class="text-2xl font-bold text-primary-dark">Rp{{ number_format($cart->sum('subtotal'),0,',','.') }}</p>
        </div>
        <a href="{{ route('checkout.index') }}" class="bg-accent hover:brightness-95 text-primary-dark font-bold px-6 py-3 rounded-md">
            Lanjut ke Checkout &rarr;
        </a>
    </div>
@endif
@endsection
