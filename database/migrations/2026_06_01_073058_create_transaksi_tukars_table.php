<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_tukar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Menghubungkan ke ID di tabel penggunas
            $table->string('provider');
            $table->integer('nominal');
            $table->string('no_hp');
            $table->timestamps();

            // Relasi foreign key ke tabel penggunas
            $table->foreign('user_id')->references('id')->on('penggunas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_tukar');
    }
};