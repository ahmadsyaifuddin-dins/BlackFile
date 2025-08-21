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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan hash dari master password.
            // Nullable karena pengguna harus membuatnya secara manual terlebih dahulu.
            $table->string('master_password')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void    
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom master_password.
            $table->dropColumn('master_password');
        });
    }
};
