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
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('codename')->unique()->nullable();
            $table->string('category'); // e.g., SCP, Mythological, Alien
            $table->string('rank')->nullable(); // e.g., Keter, Archangel, Class-A Threat
            $table->string('origin')->nullable(); // e.g., Norse Mythology, Site-19, Zeta Reticuli
            $table->text('description');
            $table->text('abilities')->nullable();
            $table->text('weaknesses')->nullable();
            $table->string('status')->default('UNKNOWN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};