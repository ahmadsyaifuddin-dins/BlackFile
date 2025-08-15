<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Friend;
use App\Models\User;

class FriendSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'ahmad')->first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Ahmad',
                'username' => 'ahmads123',
                'codename' => 'El Absolute',
                'email' => 'ahmad@example.com',
                'password' => bcrypt('password'),
                'role_id' => 1, // Director
            ]);
        }

        // Level 1
        $rio = Friend::create([
            'user_id' => $user->id,
            'name' => 'Rio',
            'codename' => 'EagleOne',
            'parent_id' => null,
        ]);

        $dina = Friend::create([
            'user_id' => $user->id,
            'name' => 'Dina',
            'codename' => 'FoxEyes',
            'parent_id' => null,
        ]);

        // Level 2 (anak dari Rio)
        $budi = Friend::create([
            'user_id' => $user->id,
            'name' => 'Budi',
            'codename' => 'WolfClaw',
            'parent_id' => $rio->id,
        ]);

        // Level 3 (anak dari Budi)
        Friend::create([
            'user_id' => $user->id,
            'name' => 'Sari',
            'codename' => 'NightOwl',
            'parent_id' => $budi->id,
        ]);

        // Level 2 (anak dari Dina)
        Friend::create([
            'user_id' => $user->id,
            'name' => 'Andi',
            'codename' => 'SteelFang',
            'parent_id' => $dina->id,
        ]);
    }
}
