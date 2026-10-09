<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            // Visibilitas EKSTERNAL (link publik "/s/{token}"). Terpisah dari
            // is_public (visibilitas internal antar user sistem). Link eksternal
            // hanya bisa AKTIF jika is_public internal juga AKTIF.
            $table->boolean('is_shared')->default(false)->after('is_public');
        });
    }

    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropColumn('is_shared');
        });
    }
};