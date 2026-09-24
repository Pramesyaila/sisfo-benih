@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <span class="page-header__eyebrow">Akun pelanggan</span>
    <h1 class="page-header__title">Profil saya</h1>
    <p class="page-header__description">Kelola informasi dasar akun yang digunakan untuk:katalog dan pesanan.</p>
</div>

<section class="profile-hero">
    <div class="profile-hero__identity">
        @if ($user->profile_photo)
            <img class="profile-avatar !rounded-[18px] !border-0 object-cover" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto profil {{ $user->name }}">
        @else
            <span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        @endif
        <div class="profile-hero__copy">
            <h2 class="profile-hero__name">{{ $user->name }}</h2>
            <p class="profile-hero__meta">{{ $user->email }} &middot; {{ $user->roleLabel() }}</p>
        </div>
    </div>
    <a class="btn btn--secondary" href="{{ route('profile.edit') }}">
        <x-customer.icon name="user" :size="15"></x-customer.icon>
        Edit profil
    </a>
</section>

<div class="profile-grid">
    <section class="surface profile-section">
        <div class="flex items-center justify-between gap-3">
            <div>
                <span class="section-kicker">Informasi akun</span>
                <h2 class="surface__title">Data pribadi</h2>
            </div>
            <x-customer.icon name="user" :size="19" class="text-[var(--forest-700)]"></x-customer.icon>
        </div>
        <div class="profile-list mt-4">
            <div class="profile-row">
                <span class="profile-row__label">Nama lengkap</span>
                <span class="profile-row__value">{{ $user->name }}</span>
            </div>
            <div class="profile-row">
                <span class="profile-row__label">Email</span>
                <span class="profile-row__value">{{ $user->email }}</span>
            </div>
            <div class="profile-row">
                <span class="profile-row__label">Nomor telepon</span>
                <span class="profile-row__value">{{ $user->phone ?: 'Belum diisi' }}</span>
            </div>
            <div class="profile-row">
                <span class="profile-row__label">NIK</span>
                <span class="profile-row__value">{{ $user->nik ?: 'Belum diisi' }}</span>
            </div>
            <div class="profile-row">
                <span class="profile-row__label">Domisili</span>
                <span class="profile-row__value">{{ $user->domisili ?: 'Belum diisi' }}</span>
            </div>
            <div class="profile-row">
                <span class="profile-row__label">Alamat</span>
                <span class="profile-row__value">{{ $user->alamat ?: 'Belum diisi' }}</span>
            </div>
        </div>
    </section>

    <aside class="profile-actions">
        <section class="surface profile-section">
            <span class="section-kicker">Akses cepat</span>
            <h2 class="surface__title">Lanjutkan aktivitas</h2>
            <p class="surface__subtitle mt-1">Kelola pesanan atau cari produk baru.</p>
            <div class="mt-4 grid gap-2">
                <a class="btn btn--primary w-full justify-start" href="{{ route('orders.index') }}">
                    <x-customer.icon name="clipboard" :size="16"></x-customer.icon>
                    Lihat pesanan saya
                </a>
                <a class="btn btn--secondary w-full justify-start" href="{{ route('catalog.index') }}">
                    <x-customer.icon name="leaf" :size="16"></x-customer.icon>
                    Jelajahi katalog
                </a>
            </div>
        </section>
        <div class="profile-note">
            <strong class="block text-[var(--forest-900)] mb-1">Status akun</strong>
            Akun Anda berstatus <strong>{{ $user->is_active ? 'aktif' : 'tidak aktif' }}</strong>. Gunakan data yang valid agar proses pesanan dapat berjalan dengan jelas.
        </div>
    </aside>
</div>
@endsection
