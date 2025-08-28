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
        Schema::table('credits', function (Blueprint $table) {
            // Mengubah kolom 'name' menjadi 'names' dengan tipe JSON
            $table->json('names')->after('role');
        });

        // Opsi: Jika Anda ingin memigrasikan data lama (jika ada)
        // DB::table('credits')->whereNotNull('name')->cursor()->each(function ($credit) {
        //     DB::table('credits')->where('id', $credit->id)->update([
        //         'names' => json_encode([$credit->name])
        //     ]);
        // });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->string('name')->after('role')->nullable();
        });

        // Logika untuk mengembalikan data (jika diperlukan)

        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('names');
        });
    }
};
