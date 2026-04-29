<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setor_sampahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['plastik', 'kertas', 'logam']);
            $table->float('berat'); // kg
            $table->integer('harga'); // total harga
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setor_sampahs');
    }
};
