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
        Schema::create('fund_sources', function (Blueprint $table) {
            $table->id();

            // Pemilik sumber dana. Data bersifat per-agen.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Nama sumber dana: Kotak Uang, Dompet, Seabank, Dana, dll.
            $table->string('name');

            // Saldo awal saat sumber dana pertama didaftarkan.
            $table->decimal('initial_balance', 16, 2)->default(0);

            $table->string('description')->nullable();

            $table->timestamps();

            // Satu agen tidak boleh punya dua sumber dana dengan nama sama.
            $table->unique(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_sources');
    }
};
