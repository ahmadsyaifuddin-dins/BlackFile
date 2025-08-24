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
        Schema::table('encrypted_contacts', function (Blueprint $table) {
            // Menambahkan kolom untuk path foto profil.
            // Tidak dienkripsi agar mudah diakses untuk ditampilkan.
            $table->string('profile_photo_path')->nullable()->after('codename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encrypted_contacts', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });
    }
};
