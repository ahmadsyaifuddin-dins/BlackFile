<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DefaultMusic;

class DefaultMusicSeeder extends Seeder
{
    public function run(): void
    {
        DefaultMusic::create([
            'name' => 'Hopeful Overture',
            'path' => 'music/default/hopeful.mp3',
        ]);
        DefaultMusic::create([
            'name' => 'Cinematic Tension',
            'path' => 'music/default/tension.mp3',
        ]);
        DefaultMusic::create([
            'name' => 'Reflective Piano',
            'path' => 'music/default/reflective.mp3',
        ]);
    }
}