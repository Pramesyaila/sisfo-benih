<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pnbp_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('bill_number')->unique();
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['belum_dibayar', 'menunggu_verifikasi', 'lunas', 'ditolak'])->default('belum_dibayar');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pnbp_bills');
    }
};
