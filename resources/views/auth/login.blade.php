@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded-xl p-8 mt-6 border-t-4 border-accent">
    <div class="text-center mb-6">
        <div class="mx-auto bg-accent-light text-primary-dark w-14 h-14 rounded-full flex items-center justify-center text-2xl mb-3">🌾</div>
        <h1 class="text-xl font-bold text-primary-dark">Masuk ke Sistem</h1>
        <p class="text-sm text-gray-500 mt-1">
            Satu halaman login untuk Konsumen maupun Petugas/Pengelola.<br>
            Sistem akan mengarahkan anda secara otomatis sesuai hak akses.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kata Sandi</label>
            <input type="password" name="password" required
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
        </div>
        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-md transition">
            Masuk
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun (konsumen)?
        <a href="{{ route('register') }}" class="text-primary-dark font-semibold hover:underline">Daftar di sini</a>
    </p>

    <div class="mt-6 bg-accent-light/60 rounded-md p-3 text-xs text-gray-600">
        <strong>Akun contoh (hasil seeder):</strong><br>
        Konsumen: konsumen@benih.test / password<br>
        Petugas Layanan: layanan@benih.test / password<br>
        Petugas PNBP: pnbp@benih.test / password<br>
        Manager/Gudang: gudang@benih.test / password
    </div>
</div>
@endsection
