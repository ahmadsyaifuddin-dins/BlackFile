<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'director'], // username unik
            [
                'name' => 'Director Utama',
                'codename' => 'El Absolute', // nama sandi
                'email' => 'director@blackfile.com', // opsional
                'password' => Hash::make('black123'),
                'role_id' => 1, // Director
            ]
        );

        User::firstOrCreate(
            ['username' => 'agent01'],
            [
                'name' => 'Agent Rahasia',
                'codename' => 'ShadowFox',
                'email' => 'agent@blackfile.com',
                'password' => Hash::make('agent123'),
                'role_id' => 2, // Agent
            ]
        );

        User::firstOrCreate(
            ['username' => 'medic'],
            [
                'name' => 'Tim Support',
                'codename' => 'MedicOne',
                'email' => 'support@blackfile.com',
                'password' => Hash::make('support123'),
                'role_id' => 3, // Support
            ]
        );
    }
}
