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
        Schema::create('archives', function (Blueprint $table) {
            $table->id(); // Kunci utama (Primary Key)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke pengguna yang memiliki arsip ini

            $table->string('name'); // Nama dari arsip (mis: "Laporan Keuangan Q4", "Installer Game ABC")
            $table->text('description')->nullable(); // Deskripsi opsional untuk arsip

            $table->string('type'); // Tipe arsip, mis: 'file' atau 'url'
            
            // Kolom ini untuk menyimpan path jika tipenya 'file'
            $table->string('file_path')->nullable(); 
            $table->string('mime_type')->nullable(); // mis: 'application/pdf', 'image/jpeg'
            $table->unsignedBigInteger('size')->nullable(); // Ukuran file dalam bytes

            // Kolom ini untuk menyimpan link jika tipenya 'url'
            // Menggunakan tipe JSON agar bisa menyimpan satu atau banyak link (untuk tautan multi-bagian)
            $table->json('links')->nullable();

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};