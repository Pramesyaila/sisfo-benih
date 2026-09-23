@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('catalog.index') }}" class="text-sm text-primary-dark hover:underline">&larr; Kembali ke katalog</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden grid md:grid-cols-2">
    <div class="h-64 md:h-full bg-accent-light flex items-center justify-center text-7xl">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover" alt="{{ $product->name }}">
        @else
            🌱
        @endif
    </div>
    <div class="p-6 flex flex-col">
        <span class="badge bg-accent-light text-primary-dark w-fit mb-2">{{ $product->category->name }}</span>
        <h1 class="text-2xl font-bold text-black">{{ $product->name }}</h1>
        <p class="text-gray-600 mt-2 text-sm leading-relaxed">{{ $product->description }}</p>

        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div class="bg-base rounded-md p-3 border border-gray-100">
                <p class="text-gray-500">Kemasan</p>
                <p class="font-semibold">{{ $product->packagingLabel() }}</p>
            </div>
            <div class="bg-base rounded-md p-3 border border-gray-100">
                <p class="text-gray-500">Stok tersedia</p>
                <p class="font-semibold {{ $product->isLowStock() ? 'text-accent' : '' }}">{{ $product->stock }} {{ $product->packaging_unit }}</p>
            </div>
        </div>

        <p class="text-3xl font-bold text-primary-dark mt-4">{{ $product->formattedPrice() }} <span class="text-sm text-gray-500 font-normal">/ {{ $product->packaging_unit }}</span></p>

        @auth
            @if(auth()->user()->isKonsumen())
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-5 flex items-center gap-3">
                        @csrf
                        <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}"
                            class="w-24 rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
                        <button class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2.5 rounded-md transition">
                            + Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <p class="mt-5 text-red-600 font-medium">Stok sedang habis.</p>
                @endif
            @endif
        @else
            <a href="{{ route('login') }}" class="mt-5 inline-block bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2.5 rounded-md transition w-fit">
                Masuk untuk memesan
            </a>
        @endauth
    </div>
</div>

@if($related->isNotEmpty())
<div class="mt-10">
    <h2 class="font-semibold text-lg mb-3">Produk sejenis</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($related as $r)
            <a href="{{ route('catalog.show', $r) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">
                <div class="h-24 bg-accent-light rounded-md flex items-center justify-center text-3xl mb-2">🌱</div>
                <p class="text-sm font-medium">{{ $r->name }}</p>
                <p class="text-xs text-primary-dark font-semibold">{{ $r->formattedPrice() }}</p>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
