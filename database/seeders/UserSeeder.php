<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh untuk tiap sisi/role. Password bisa diganti setelah login pertama.
        User::create([
            'name' => 'Petugas Layanan',
            'email' => 'layanan@benih.test',
            'password' => Hash::make('password'),
            'role' => 'petugas_layanan',
        ]);

        User::create([
            'name' => 'Petugas Pengelola PNBP',
            'email' => 'pnbp@benih.test',
            'password' => Hash::make('password'),
            'role' => 'petugas_pnbp',
        ]);

        User::create([
            'name' => 'Manager / Petugas Gudang',
            'email' => 'gudang@benih.test',
            'password' => Hash::make('password'),
            'role' => 'manager_gudang',
        ]);

        User::create([
            'name' => 'Pramesyaila (Konsumen Contoh)',
            'email' => 'konsumen@benih.test',
            'password' => Hash::make('password'),
            'role' => 'konsumen',
            'phone' => '081234567890',
            'domisili' => 'Bogor',
            'alamat' => 'Jl. Contoh No. 1, Bogor',
        ]);
    }
}
