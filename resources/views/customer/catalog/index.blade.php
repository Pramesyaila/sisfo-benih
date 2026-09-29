@extends('layouts.app')

@section('title', 'Katalog Benih & Bibit')

@section('content')
<section class="hero">
    <div class="hero__copy">
        <span class="hero__eyebrow">Benih &amp; bibit pilihan</span>
        @auth
            @if (auth()->user()->isKonsumen())
                <h1 class="hero__title">Selamat datang,<br>{{ auth()->user()->name }}.</h1>
                <p class="hero__description">Temukan benih dan bibit yang Anda butuhkan, lalu pantau perjalanan pesanan Anda dalam satu tempat.</p>
            @else
                <h1 class="hero__title">Katalog yang membantu<br>pertanian tumbuh.</h1>
                <p class="hero__description">Informasi produk, kemasan, dan ketersediaan benih serta bibit dalam satu katalog yang mudah dijelajahi.</p>
            @endif
        @else
            <h1 class="hero__title">Benih pilihan untuk<br>langkah tumbuh berikutnya.</h1>
            <p class="hero__description">Jelajahi katalog benih dan bibit, pilih yang sesuai, lalu ajukan pesanan dengan proses yang jelas dan mudah dilacak.</p>
        @endauth
        <div class="hero__actions">
            <a class="btn btn--gold" href="#daftar-produk">
                Jelajahi produk
                <x-customer.icon name="arrow-right" :size="16"></x-customer.icon>
            </a>
            @auth
                @if (auth()->user()->isKonsumen())
                    <a class="btn btn--secondary" href="{{ route('orders.index') }}">
                        Lihat pesanan saya
                    </a>
                @endif
            @else
                <a class="btn btn--secondary" href="{{ route('register') }}">Buat akun pelanggan</a>
            @endauth
        </div>
        <div class="hero__note">
            <x-customer.icon name="shield" :size="15"></x-customer.icon>
            <span>Informasi produk dan status pesanan tersusun dalam satu alur.</span>
        </div>
    </div>
    <div class="hero__art" aria-hidden="true">
        <span class="hero__art-stamp">-grown with care</span>
        <span class="hero__art-orbit"></span>
        <svg class="hero__art-sprout" width="205" height="205" viewBox="0 0 220 220" fill="none">
            <path d="M111 190V78" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
            <path d="M111 125C67 127 43 105 42 62c43-2 67 20 69 63Z" fill="currentColor" opacity=".83"></path>
            <path d="M111 105c2-45 29-68 72-70 1 44-26 68-72 70Z" fill="currentColor" opacity=".6"></path>
            <path d="M111 151c34 0 56-17 57-48-34-2-56 16-57 48Z" fill="currentColor" opacity=".72"></path>
            <path d="M75 190h73" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
            <path d="M31 190c32-13 126-13 158 0" stroke="#FEF9C3" stroke-width="2" stroke-linecap="round" opacity=".8"></path>
        </svg>
        <div class="hero__art-label">
            <strong>Siap tumbuh</strong>
            <span>Pilih produk yang sesuai untuk kebutuhan Anda.</span>
        </div>
    </div>
</section>

<section class="trust-strip" aria-label="Keunggulan katalog">
    <div class="trust-item">
        <span class="trust-item__icon"><x-customer.icon name="leaf" :size="19"></x-customer.icon></span>
        <span class="trust-item__copy"><strong>Produk terkurasi</strong><span>Benih dan bibit dengan informasi yang jelas.</span></span>
    </div>
    <div class="trust-item">
        <span class="trust-item__icon"><x-customer.icon name="package" :size="19"></x-customer.icon></span>
        <span class="trust-item__copy"><strong>Kemasan transparan</strong><span>Ukuran, satuan, dan harga terlihat sebelum memesan.</span></span>
    </div>
    <div class="trust-item">
        <span class="trust-item__icon"><x-customer.icon name="clipboard" :size="19"></x-customer.icon></span>
        <span class="trust-item__copy"><strong>Status mudah dilacak</strong><span>Perjalanan pesanan tersedia di akun Anda.</span></span>
    </div>
</section>

<section id="daftar-produk">
    <div class="catalog-toolbar">
        <div class="catalog-toolbar__heading">
            <span class="section-kicker">Pilihan untuk Anda</span>
            <h2>Daftar produk</h2>
            <p>Temukan produk yang sesuai, lalu klik untuk melihat detail lengkapnya.</p>
        </div>
        <div class="catalog-toolbar__meta">{{ $products->total() }} produk ditemukan</div>
    </div>

    <div class="filter-list" aria-label="Filter kategori">
        <a class="filter-link {{ !request('category') ? 'filter-link--active' : '' }}" href="{{ route('catalog.index', request()->except('category', 'page')) }}">
            Semua produk
        </a>
        @foreach ($categories as $category)
            <a class="filter-link {{ request('category') === $category->slug ? 'filter-link--active' : '' }}" href="{{ route('catalog.index', array_merge(request()->except('category', 'page'), ['category' => $category->slug])) }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    @if (request('q'))
        <div class="flash flash--success mb-5" role="status">
            <span class="flash__icon"><x-customer.icon name="search" :size="17"></x-customer.icon></span>
            <div>Menampilkan hasil untuk <strong>“{{ request('q') }}”</strong>.</div>
        </div>
    @endif

    @if ($products->isEmpty())
        <x-customer.empty-state
            eyebrow="Belum ada yang cocok"
            title="Produk belum ditemukan"
            description="Coba kata kunci lain atau jelajahi semua kategori untuk menemukan produk yang Anda cari."
            action-label="Lihat semua produk"
            :action-url="route('catalog.index')"
            icon="search"
        ></x-customer.empty-state>
    @else
        <div class="product-grid">
            @foreach ($products as $product)
                <x-customer.product-card :product="$product"></x-customer.product-card>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</section>

<div class="section-divider">
    <div class="order-steps">
        <div class="order-step order-step--active">
            <span class="order-step__number">1</span>
            <span class="order-step__label">Pilih produk</span>
        </div>
        <div class="order-step">
            <span class="order-step__number">2</span>
            <span class="order-step__label">Buat pesanan</span>
        </div>
        <div class="order-step">
            <span class="order-step__number">3</span>
            <span class="order-step__label">Ikuti prosesnya</span>
        </div>
    </div>
</div>
@endsection
