@extends('layouts.app')

@section('title', 'Katalog Benih & Bibit')

@section('content')
<div class="bg-primary-dark text-white rounded-xl p-6 mb-6">
    <h1 class="text-2xl font-bold">Katalog Benih &amp; Bibit</h1>
    <p class="text-white/80 text-sm mt-1">Pilih produk, tentukan jumlah, lalu lakukan pemesanan secara online.</p>
</div>

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('catalog.index') }}"
        class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary' }}">
        Semua
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}"
            class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') === $cat->slug ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

@if($products->isEmpty())
    <div class="bg-white rounded-xl p-10 text-center text-gray-500 shadow-sm">
        Produk tidak ditemukan.
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($products as $product)
            <a href="{{ route('catalog.show', $product) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 flex flex-col">
                <div class="h-40 bg-accent-light flex items-center justify-center text-5xl">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover" alt="{{ $product->name }}">
                    @else
                        🌱
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <span class="badge bg-accent-light text-primary-dark w-fit mb-1">{{ $product->category->name }}</span>
                    <h2 class="font-semibold text-black">{{ $product->name }}</h2>
                    <p class="text-xs text-gray-500 mb-2">Kemasan: {{ $product->packagingLabel() }}</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-primary-dark font-bold">{{ $product->formattedPrice() }}</span>
                        <span class="text-xs {{ $product->isLowStock() ? 'text-accent font-semibold' : 'text-gray-500' }}">
                            Stok: {{ $product->stock }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endif
@endsection
