@extends('layouts.app')

@section('title', 'Daftar Akun Konsumen')

@section('content')
<div class="auth-layout">
    <aside class="auth-aside">
        <div>
            <span class="auth-aside__eyebrow">Mulai dari langkah kecil</span>
            <h1 class="auth-aside__title">Ruang Anda untuk<br>menumbuhkan pilihan.</h1>
            <p class="auth-aside__copy">Buat akun pelanggan untuk menyimpan keranjang, melihat pesanan, dan menerima pembaruan dari setiap proses.</p>
        </div>
        <div class="auth-aside__art">
            <x-customer.icon name="leaf" :size="96"></x-customer.icon>
            <small>terhubung · transparan · mudah</small>
        </div>
    </aside>

    <section class="auth-card">
        <div class="auth-card__header">
            <span class="auth-card__eyebrow">Akun pelanggan</span>
            <h2 class="auth-card__title">Buat akun baru</h2>
            <p class="auth-card__description">Registrasi ini digunakan untuk katalog dan pemesanan benih atau bibit.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div>
                <label class="form-label" for="register-name">Nama lengkap</label>
                <input id="register-name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Nama lengkap Anda">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="register-email">Email</label>
                <input id="register-email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="form-label" for="register-phone">Nomor telepon <span class="font-normal text-[var(--muted)]">(opsional)</span></label>
                <input id="register-phone" class="form-input" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="Contoh: 0812xxxxxxx">
                @error('phone')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="register-password">Kata sandi</label>
                    <input id="register-password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 6 karakter">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="register-password-confirmation">Konfirmasi kata sandi</label>
                    <input id="register-password-confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                </div>
            </div>
            <button class="btn btn--primary w-full" type="submit">
                Buat akun pelanggan
                <x-customer.icon name="arrow-right" :size="16"></x-customer.icon>
            </button>
        </form>

        <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </section>
</div>
@endsection
