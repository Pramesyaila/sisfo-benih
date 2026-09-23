<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('packaging_unit')->default('bungkus'); // satuan kemasan
            $table->string('packaging_size')->nullable(); // misal "5 kg"
            $table->unsignedBigInteger('price')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10); // batas stok menipis
            $table->string('image')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
