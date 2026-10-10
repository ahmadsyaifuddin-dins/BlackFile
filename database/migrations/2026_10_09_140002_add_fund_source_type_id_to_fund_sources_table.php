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
        Schema::table('fund_sources', function (Blueprint $table) {
            // Referensi ke data master. Nama sumber dana akan diambil dari sini
            // agar konsisten (user tinggal memilih dari dropdown).
            $table->foreignId('fund_source_type_id')
                ->nullable()
                ->after('user_id')
                ->constrained('fund_source_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_sources', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fund_source_type_id');
        });
    }
};
