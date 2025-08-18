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
                'name' => 'Ahmad Syaifuddin',
                'codename' => 'El Absolute',
                'email' => 'ahmadsyai598@gmail.com', // opsional
                'password' => Hash::make('ahmads1230906'),
                'specialization' => 'System Architecture & Counter-Intelligence',
                'qoutes' => 'The future is not set in stone, it is shaped by our actions.',
                'role_id' => 1, // Director
            ]
        );

        User::firstOrCreate(
            ['username' => 'agent-07'],
            [
                'name' => 'M. Haldi',
                'codename' => 'ANTIMATERI',
                'email' => 'haldi@gmail.com',
                'password' => Hash::make('password'),
                'specialization' => 'Open-Source Intelligence (OSINT) & Data Extraction',
                'qoutes' => '',
                'role_id' => 2, // Agent
            ]
        );

        User::firstOrCreate(
            ['username' => 'analyst-01'],
            [
                'name' => 'Muhammad Rio Bima Saputra',
                'codename' => 'El Rio',
                'email' => 'elrio@gmail.com',
                'password' => Hash::make('password'),
                'specialization' => 'Digital Ghost & OS Manipulation',
                'qoutes' => '',
                'role_id' => 3, // Analyst
            ]
        );

        User::firstOrCreate(
            ['username' => 'agent-X9'],
            [
                'name' => 'Ryandy Rhamadhany',
                'codename' => 'Synapse-X9',
                'email' => 'ryandyrhamadhany@gmail.com',
                'password' => Hash::make('password'),
                'specialization' => 'Solution Architect & System Auditing',
                'qoutes' => '',
                'role_id' => 2, // Agent
            ]
        );

        User::firstOrCreate(
            ['username' => 'agent-21'],
            [
                'name' => 'Muhammad Aldy Rahmatillah',
                'codename' => 'Aldy Stecu',
                'email' => 'aldyr@gmail.com',
                'password' => Hash::make('password'),
                'specialization' => 'Psychological Operations (PSYOP) Specialist & Infiltration',
                'qoutes' => '',
                'role_id' => 2, // Agent
            ]   
        );

        User::firstOrCreate(
            ['username' => 'agent-41'],
            [
                'name' => 'Muhammad Maulidi',
                'codename' => 'Yatnua',
                'email' => 'mhmmdmaulidi21@gmail.com',
                'password' => Hash::make('password'),
                'specialization' => 'Field Operations & Execution',
                'qoutes' => '',
                'role_id' => 2, // Agent
            ]
        );
    }
}
