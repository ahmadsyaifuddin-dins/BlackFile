<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dark_archives', function (Blueprint $table) {
            $table->id();
            $table->string('case_code')->unique(); // Contoh: CASE-1997-BJM
            $table->string('title'); // Case #1997: Jumat Kelabu
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();

            // Rich Text Content
            $table->longText('content');

            // Status: Draft (Agent only), Declassified (Public)
            $table->enum('status', ['draft', 'declassified'])->default('draft');

            // Metadata
            $table->timestamp('incident_date')->nullable();
            $table->string('location')->nullable(); // Banjarmasin, Kalimantan Selatan

            // Engagement
            $table->unsignedBigInteger('views')->default(0); // Counter View
            $table->unsignedBigInteger('respects')->default(0); // Counter Respect/Lilin

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Agent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dark_archives');
    }
};
