@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="page-header">
    <span class="page-header__eyebrow">Langkah 3 dari 3</span>
    <h1 class="page-header__title">Checkout pesanan</h1>
    <p class="page-header__description">Tambahkan catatan bila diperlukan, lalu buat pesanan untuk memulai proses administrasi.</p>
</div>

<div class="order-steps" aria-label="Tahapan pemesanan">
    <div class="order-step order-step--done">
        <span class="order-step__number"><x-customer.icon name="check" :size="13"></x-customer.icon></span>
        <span class="order-step__label">Pilih produk</span>
    </div>
    <div class="order-step order-step--done">
        <span class="order-step__number"><x-customer.icon name="check" :size="13"></x-customer.icon></span>
        <span class="order-step__label">Keranjang</span>
    </div>
    <div class="order-step order-step--active">
        <span class="order-step__number">3</span>
        <span class="order-step__label">Checkout</span>
    </div>
</div>

<div class="content-layout">
    <section class="surface cart-list" aria-label="Ringkasan produk">
        <div class="surface__header">
            <h2 class="surface__title">Produk yang akan dipesan</h2>
            <p class="surface__subtitle">Harga dan jumlah mengikuti pilihan di keranjang Anda.</p>
        </div>
        @foreach ($cart as $row)
            <div class="cart-row">
                <div class="cart-row__art">
                    <x-customer.product-art :product="$row['product']" size="compact"></x-customer.product-art>
                </div>
                <div class="cart-row__content">
                    <p class="cart-row__name">{{ $row['product']->name }}</p>
                    <p class="cart-row__meta">{{ $row['qty'] }} {{ $row['product']->packaging_unit }} &times; {{ $row['product']->formattedPrice() }}</p>
                </div>
                <div class="cart-row__spacer"></div>
                <div class="cart-row__subtotal">Rp{{ number_format($row['subtotal'], 0, ',', '.') }}</div>
            </div>
        @endforeach
        <div class="flex items-center justify-between px-4 py-4 bg-[var(--surface-muted)]">
            <span class="text-xs font-semibold text-[var(--muted)]">Total item</span>
            <span class="text-sm font-extrabold text-[var(--forest-900)]">{{ $cart->sum('qty') }} item</span>
        </div>
    </section>

    <aside class="surface summary-card">
        <h2 class="summary-card__title">Detail pemesanan</h2>
        <div class="summary-row">
            <span>Subtotal</span>
            <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
        </div>
        <div class="summary-row summary-total">
            <span>Total pesanan</span>
            <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
            @csrf
            <div>
                <label class="form-label" for="checkout-notes">Catatan untuk petugas <span class="font-normal text-[var(--muted)]">(opsional)</span></label>
                <textarea id="checkout-notes" class="form-textarea" name="notes" rows="4" maxlength="1000" placeholder="Contoh: kebutuhan planting, waktu pengambilan, atau catatan lainnya.">{{ old('notes') }}</textarea>
                <p class="form-hint">Catatan membantu petugas memahami kebutuhan pesanan Anda.</p>
            </div>
            <button class="btn btn--primary w-full" type="submit">
                Buat pesanan
                <x-customer.icon name="arrow-right" :size="16"></x-customer.icon>
            </button>
        </form>

        <div class="summary-note">
            <div class="flex items-start gap-2">
                <x-customer.icon name="info" :size="15"></x-customer.icon>
                <span>Setelah pesanan dibuat, petugas layanan akan memproses administrasi, membuat tagihan, dan memverifikasi bukti pembayaran Anda.</span>
            </div>
        </div>
    </aside>
</div>
@endsection
