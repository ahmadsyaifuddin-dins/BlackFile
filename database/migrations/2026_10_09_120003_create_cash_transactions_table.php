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
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();

            // Pemilik transaksi. Data bersifat per-agen.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Sumber dana tempat uang masuk/keluar.
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')->nullOnDelete();

            // Jika transaksi ini dihasilkan otomatis oleh sebuah piutang.
            $table->foreignId('receivable_id')->nullable()->constrained('receivables')->nullOnDelete();

            // 'in' = kas masuk, 'out' = kas keluar.
            $table->enum('type', ['in', 'out']);

            $table->decimal('amount', 16, 2);

            $table->string('category')->nullable();

            $table->text('description')->nullable();

            // Kapan transaksi terjadi (tanggal + jam).
            $table->dateTime('transacted_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
