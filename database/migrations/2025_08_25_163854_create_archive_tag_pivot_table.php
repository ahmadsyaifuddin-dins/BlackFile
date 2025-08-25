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
        Schema::create('archive_tag', function (Blueprint $table) {
            // foreignId merujuk ke id di tabel archives
            $table->foreignId('archive_id')->constrained()->onDelete('cascade');
    
            // foreignId merujuk ke id di tabel tags
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
    
            // Set primary key gabungan untuk mencegah duplikat
            $table->primary(['archive_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_tag_pivot');
    }
};
