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
        Schema::create('encrypted_contacts', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke pengguna yang memiliki kontak ini.
            // onDelete('cascade') berarti jika seorang agen dihapus, semua kontaknya juga akan terhapus.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Codename tidak dienkripsi agar bisa ditampilkan di daftar.
            $table->string('codename');
            
            // Kolom ini akan menyimpan semua data sensitif dalam format terenkripsi.
            // Menggunakan 'longText' untuk menampung data JSON yang besar.
            $table->longText('encrypted_payload');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_contacts');
    }
};
