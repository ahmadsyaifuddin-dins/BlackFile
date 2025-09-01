<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_musics', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama musik, misal: "Cinematic Tension"
            $table->string('path'); // Path ke file musik, misal: "music/default/cinematic.mp3"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_musics');
    }
};