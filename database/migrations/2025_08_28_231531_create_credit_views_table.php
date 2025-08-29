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
        Schema::create('credit_views', function (Blueprint $table) {
            $table->id();
            // Foreign key ke pemilik halaman credit
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            // Foreign key ke pengunjung (jika dia adalah user terdaftar)
            $table->foreignId('visitor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('viewed_at')->useCurrent();
            
            // Kita tidak butuh updated_at untuk log sederhana
            // $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_views');
    }
};
