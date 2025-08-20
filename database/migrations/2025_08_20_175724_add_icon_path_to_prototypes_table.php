<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prototypes', function (Blueprint $table) {
            // Tambahkan kolom baru setelah 'cover_image_path'
            $table->string('icon_path')->nullable()->after('cover_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('prototypes', function (Blueprint $table) {
            $table->dropColumn('icon_path');
        });
    }
};