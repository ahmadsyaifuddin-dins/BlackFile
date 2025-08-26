<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Director', 'alias' => 'Pimpinan Intelijen'],
            ['name' => 'Agent', 'alias' => 'Operasi Lapangan'],
            ['name' => 'Technician', 'alias' => 'Spesialis Teknis'],
            ['name' => 'Analyst', 'alias' => 'Pengolah Informasi'],

            // [BARU] Role untuk sistem registrasi
            ['name' => 'applicant', 'alias' => 'Unverified Operative'],
            ['name' => 'affiliate', 'alias' => 'Allied Operative'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
