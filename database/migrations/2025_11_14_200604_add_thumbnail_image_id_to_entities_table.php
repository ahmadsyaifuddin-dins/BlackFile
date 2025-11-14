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
        Schema::table('entities', function (Blueprint $table) {
            $table->unsignedBigInteger('thumbnail_image_id')->nullable()->after('status');

            // Tambahkan foreign key constraint
            // Ini akan menunjuk ke 'id' di tabel 'entity_images'
            // 'onDelete('set null')' berarti jika gambar dihapus, kolom ini akan otomatis jadi NULL
            $table->foreign('thumbnail_image_id')
                  ->references('id')
                  ->on('entity_images')
                  ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_image_id']);
            $table->dropColumn('thumbnail_image_id');
        });
    }
};
