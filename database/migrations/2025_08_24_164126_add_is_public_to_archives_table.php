<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Tambahkan kolom is_public setelah kolom 'type'
            // Default-nya false (Private) agar lebih aman
            $table->boolean('is_public')->default(false)->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};