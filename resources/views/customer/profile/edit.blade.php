@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="page-header">
    <a class="breadcrumb__link inline-flex items-center gap-1 mb-3" href="{{ route('profile.show') }}">
        <x-customer.icon name="chevron-right" :size="13" class="rotate-180"></x-customer.icon>
        Kembali ke profil
    </a>
    <span class="page-header__eyebrow">Akun pelanggan</span>
    <h1 class="page-header__title">Edit profil</h1>
    <p class="page-header__description">Perbarui informasi yang membantu kami memahami kebutuhan dan	data pemesanan Anda.</p>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="content-layout">
    @csrf
    @method('PUT')

    <div class="content-main">
        <section class="surface p-5 md:p-6">
            <div class="flex items-center gap-3 border-b border-[var(--line)] pb-4">
                <span class="trust-item__icon"><x-customer.icon name="user" :size="19"></x-customer.icon></span>
                <div>
                    <h2 class="surface__title">Informasi dasar</h2>
                    <p class="surface__subtitle">Pastikan data Anda selalu tersimpan dengan benar.</p>
                </div>
            </div>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="form-label" for="profile-name">Nama lengkap</label>
                    <input id="profile-name" class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="profile-email">Email</label>
                    <input id="profile-email" class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="profile-phone">Nomor telepon</label>
                    <input id="profile-phone" class="form-input" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label" for="profile-nik">NIK</label>
                    <input id="profile-nik" class="form-input" type="text" name="nik" value="{{ old('nik', $user->nik) }}">
                    @error('nik')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label" for="profile-domisili">Domisili</label>
                    <input id="profile-domisili" class="form-input" type="text" name="domisili" value="{{ old('domisili', $user->domisili) }}">
                    @error('domisili')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label" for="profile-alamat">Alamat</label>
                    <textarea id="profile-alamat" class="form-textarea" name="alamat" rows="4">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>
    </div>

    <aside class="content-aside">
        <section class="surface p-5 md:p-6">
            <span class="section-kicker">Foto profil</span>
            <h2 class="surface__title">Tambahkan foto</h2>
            <p class="surface__subtitle mt-1">Gunakan foto yang mudah dikenali dan tetap profesional.</p>
            <div class="mt-5 flex items-center gap-4">
                @if ($user->profile_photo)
                    <img class="profile-avatar" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto profil {{ $user->name }}">
                @else
                    <span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
                <span class="text-xs text-[var(--muted)]">JPG, PNG, atau WEBP<br>Maksimal 2 MB</span>
            </div>
            <div class="mt-4">
                <label class="form-label" for="profile-photo">Pilih foto baru</label>
                <div class="upload-zone">
                    <x-customer.icon name="upload" :size="17"></x-customer.icon>
                    <input id="profile-photo" class="file-input" type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp,image/*">
                </div>
                @error('profile_photo')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </section>
        <div class="profile-note">
            Perubahan data akan langsung digunakan pada halaman profil dan proses pemesanan berikutnya.
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a class="btn btn--secondary flex-1 justify-center" href="{{ route('profile.show') }}">Batal</a>
            <button class="btn btn--primary flex-1 justify-center" type="submit">
                <x-customer.icon name="check" :size="15"></x-customer.icon>
                Simpan perubahan
            </button>
        </div>
    </aside>
</form>
@endsection
