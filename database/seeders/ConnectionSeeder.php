<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('connections')->insert([
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 1,
                'target_type' => 'App\\Models\\User',
                'target_id' => 2,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:14:04',
                'updated_at' => '2025-08-18 16:14:04'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 1,
                'target_type' => 'App\\Models\\User',
                'target_id' => 5,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:16:16',
                'updated_at' => '2025-08-18 16:16:16'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 1,
                'target_type' => 'App\\Models\\User',
                'target_id' => 3,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:16:24',
                'updated_at' => '2025-08-18 16:16:24'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 1,
                'target_type' => 'App\\Models\\User',
                'target_id' => 4,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:16:33',
                'updated_at' => '2025-08-18 16:16:33'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 1,
                'target_type' => 'App\\Models\\User',
                'target_id' => 6,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:16:52',
                'updated_at' => '2025-08-18 16:16:52'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 2,
                'target_type' => 'App\\Models\\User',
                'target_id' => 1,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:18:18',
                'updated_at' => '2025-08-18 16:18:18'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 3,
                'target_type' => 'App\\Models\\User',
                'target_id' => 1,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:19:09',
                'updated_at' => '2025-08-18 16:19:09'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 5,
                'target_type' => 'App\\Models\\User',
                'target_id' => 1,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:19:53',
                'updated_at' => '2025-08-18 16:19:53'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 4,
                'target_type' => 'App\\Models\\User',
                'target_id' => 1,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:20:30',
                'updated_at' => '2025-08-18 16:20:30'
            ],
            [
                'source_type' => 'App\\Models\\User',
                'source_id' => 6,
                'target_type' => 'App\\Models\\User',
                'target_id' => 1,
                'relationship_type' => 'operative',
                'created_at' => '2025-08-18 16:21:06',
                'updated_at' => '2025-08-18 16:21:06'
            ]
        ]);
    }
}