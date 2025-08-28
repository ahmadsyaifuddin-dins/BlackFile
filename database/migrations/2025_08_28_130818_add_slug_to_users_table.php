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
            // Menambahkan kolom 'slug' setelah kolom 'codename'
            // Dibuat nullable() agar tidak error pada data user yang sudah ada
            // Dibuat unique() agar setiap user memiliki URL publik yang unik
            $table->string('slug')->nullable()->unique()->after('codename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
