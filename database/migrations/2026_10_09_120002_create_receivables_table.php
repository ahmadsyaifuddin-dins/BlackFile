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
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();

            // Pemilik catatan piutang. Data bersifat per-agen.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Sumber dana terkait (opsional). Dipakai untuk otomatis membuat
            // kas keluar saat piutang dibuat dan kas masuk saat piutang lunas.
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')->nullOnDelete();

            // Nama orang yang berhutang ("si anu").
            $table->string('debtor_name');

            $table->decimal('amount', 16, 2);

            $table->text('description')->nullable();

            // Piutang "masa lalu": tidak mengurangi saldo saat diinput karena
            // uangnya sudah keluar sebelum sistem ini dipakai.
            $table->boolean('is_legacy')->default(false);

            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');

            // Kapan piutang terjadi (tanggal + jam).
            $table->dateTime('transacted_at');

            // Kapan piutang dilunasi.
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
