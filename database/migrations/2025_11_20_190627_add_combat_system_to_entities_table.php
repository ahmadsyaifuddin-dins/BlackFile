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
            // 1. Power Tier: 1 (Terkuat/Absolut) s/d 10 (Terlemah/Manusia Biasa)
            // Default 10 agar entitas baru dianggap lemah dulu sebelum diedit
            $table->unsignedTinyInteger('power_tier')->default(10)->after('status');

            // 2. Tipe Pertarungan:
            // 'AGGRESSOR' = Makhluk hidup/sentient yang menyerang aktif (Default)
            // 'HAZARD'    = Benda mati/Lokasi/Konsep yang menyerang pasif (via efek/jebakan)
            $table->string('combat_type')->default('AGGRESSOR')->after('power_tier');

            // 3. Combat Stats (JSON):
            // Menyimpan: strength, speed, durability, intelligence, energy, combat_skill
            $table->json('combat_stats')->nullable()->after('combat_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn(['power_tier', 'combat_type', 'combat_stats']);
        });
    }
};
