<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['konsumen', 'petugas_layanan', 'petugas_pnbp', 'manager_gudang'])
                ->default('konsumen')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('nik')->nullable()->after('phone');
            $table->string('domisili')->nullable()->after('nik');
            $table->text('alamat')->nullable()->after('domisili');
            $table->boolean('is_active')->default(true)->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'nik', 'domisili', 'alamat', 'is_active']);
        });
    }
};
