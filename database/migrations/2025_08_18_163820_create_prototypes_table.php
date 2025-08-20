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
        Schema::create('prototypes', function (Blueprint $table) {
            $table->id();

            // Kolom untuk identitas proyek
            $table->string('name');
            $table->string('codename')->unique();
            $table->text('description');

            // Kolom untuk klasifikasi dan status
            $table->string('status')->default('PLANNED')->index(); // e.g., PLANNED, IN_DEVELOPMENT, COMPLETED
            $table->string('project_type'); // e.g., Web Application, Mobile Application

            // Kolom teknis yang penting untuk portofolio
            $table->json('tech_stack')->nullable(); // Menyimpan stack sebagai array JSON
            $table->string('repository_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('cover_image_path')->nullable();

            // Kolom tanggal dan jam
            $table->dateTime('start_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            
            // Relasi ke pemilik berkas
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prototypes');
    }
};