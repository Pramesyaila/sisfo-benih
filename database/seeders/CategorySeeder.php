<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Padi', 'description' => 'Benih padi berbagai varietas unggul.'],
            ['name' => 'Hortikultura', 'description' => 'Benih/bibit tanaman hortikultura.'],
            ['name' => 'Ayam KUB', 'description' => 'Bibit ayam Kampung Unggul Balitbangtan (KUB).'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
