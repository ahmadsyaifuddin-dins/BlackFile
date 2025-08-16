<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Pemilik daftar teman
            $table->string('name'); // Nama asli teman
            $table->string('codename')->unique(); // Nama sandi unik
            $table->timestamps();

            // Relasi ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
