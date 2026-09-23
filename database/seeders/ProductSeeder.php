<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $padi = Category::where('name', 'Padi')->first();
        $horti = Category::where('name', 'Hortikultura')->first();
        $ayam = Category::where('name', 'Ayam KUB')->first();

        $products = [
            [$padi, 'Padi Inpari 32', '5 kg', 'bungkus', 50000, 100],
            [$padi, 'Padi Inpari 42', '5 kg', 'bungkus', 52000, 80],
            [$padi, 'Padi Ciherang', '5 kg', 'bungkus', 48000, 60],
            [$horti, 'Benih Cabai Rawit', '10 gram', 'sachet', 15000, 200],
            [$horti, 'Benih Tomat Unggul', '10 gram', 'sachet', 17000, 150],
            [$horti, 'Bibit Bawang Merah', '1 kg', 'kemasan', 35000, 90],
            [$ayam, 'Bibit Ayam KUB (DOC)', '1 ekor', 'ekor', 8000, 300],
            [$ayam, 'Ayam KUB Dewasa Siap Ternak', '1 ekor', 'ekor', 65000, 40],
        ];

        foreach ($products as [$category, $name, $size, $unit, $price, $stock]) {
            Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(4),
                'description' => "Produk {$name} yang tersedia melalui katalog resmi instansi.",
                'packaging_unit' => $unit,
                'packaging_size' => $size,
                'price' => $price,
                'stock' => $stock,
                'min_stock' => 15,
                'status' => 'aktif',
            ]);
        }
    }
}
