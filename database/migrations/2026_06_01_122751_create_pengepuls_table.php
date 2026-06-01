<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengepuls', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('nama_toko')->nullable();
            $table->string('kategori_sampah')->nullable(); // Menyimpan data array/json kategori
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengepuls');
    }
};