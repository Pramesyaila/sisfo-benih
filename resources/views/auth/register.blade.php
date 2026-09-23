@extends('layouts.app')

@section('title', 'Daftar Akun Konsumen')

@section('content')
<div class="max-w-md mx-auto bg-white shadow rounded-xl p-8 mt-6 border-t-4 border-primary">
    <div class="text-center mb-6">
        <div class="mx-auto bg-accent-light text-primary-dark w-14 h-14 rounded-full flex items-center justify-center text-2xl mb-3">🧑‍🌾</div>
        <h1 class="text-xl font-bold text-primary-dark">Daftar Akun Konsumen</h1>
        <p class="text-sm text-gray-500 mt-1">Untuk memesan benih/bibit melalui katalog.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Kata Sandi</label>
            <input type="password" name="password" required
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" required
                class="w-full rounded-md border-gray-300 focus:border-primary focus:ring-primary px-3 py-2 border">
        </div>
        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-md transition">
            Daftar
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-primary-dark font-semibold hover:underline">Masuk di sini</a>
    </p>
</div>
@endsection
