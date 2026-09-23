<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'dipesan',
                'diproses',
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'pembayaran_ditolak',
                'lunas',
                'faktur_terbit',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('dipesan');
            $table->unsignedBigInteger('total')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
