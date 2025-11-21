<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_histories', function (Blueprint $table) {
            $table->id();

            // Relasi ke Entity
            $table->foreignId('attacker_id')->constrained('entities')->cascadeOnDelete();
            $table->foreignId('defender_id')->constrained('entities')->cascadeOnDelete();
            $table->foreignId('winner_id')->nullable()->constrained('entities')->nullOnDelete();

            // Data Hasil
            $table->unsignedTinyInteger('win_probability'); // 0-100
            $table->string('scenario_type')->nullable(); // 'DUEL', 'HAZARD', 'ABSOLUTE'

            // Opsional: Menyimpan log teks agar bisa dibaca ulang (Replay log)
            // Kita simpan sebagai JSON
            $table->json('logs')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_histories');
    }
};
