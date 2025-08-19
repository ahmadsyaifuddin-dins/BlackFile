<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrototypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'BlackFile',
                'codename' => 'Project BlackFile',
                'description' => 'Agent Intelligent',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'Web Application',
                'tech_stack' => '["Laravel-10","Tailwind-4","AlpineJS","Cytoscape"]',
                'repository_url' => 'https://github.com/ahmadsyaifuddin-dins/BlackFile.git',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/1755514026.png',
                'start_date' => '2025-08-15 18:46:00',
                'completed_date' => null,
                'user_id' => 1,
                'created_at' => '2025-08-18 18:47:06',
                'updated_at' => '2025-08-18 18:47:06'
            ],
            [
                'name' => 'SecureVault',
                'codename' => 'Project Titan',
                'description' => 'Encrypted file storage system with military-grade security',
                'status' => 'COMPLETED',
                'project_type' => 'Desktop Application',
                'tech_stack' => '["Electron","Rust","SQLite"]',
                'repository_url' => 'https://github.com/example/SecureVault',
                'live_url' => 'https://securevault.example.com',
                'cover_image_path' => 'uploads/prototypes/securevault.png',
                'start_date' => '2025-07-01 00:00:00',
                'completed_date' => '2025-08-10 00:00:00',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HealthTrack',
                'codename' => 'Project Vital',
                'description' => 'AI-powered health monitoring system',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'Mobile Application',
                'tech_stack' => '["Flutter","Firebase","TensorFlow Lite"]',
                'repository_url' => 'https://github.com/example/HealthTrack',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/healthtrack.jpg',
                'start_date' => '2025-08-01 00:00:00',
                'completed_date' => null,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'MarketInsight',
                'codename' => 'Project Oracle',
                'description' => 'Real-time stock market analysis tool',
                'status' => 'PLANNING',
                'project_type' => 'Web Application',
                'tech_stack' => '["React","Node.js","Python","Pandas"]',
                'repository_url' => null,
                'live_url' => null,
                'cover_image_path' => null,
                'start_date' => '2025-09-01 00:00:00',
                'completed_date' => null,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AutoDeploy',
                'codename' => 'Project Hermes',
                'description' => 'CI/CD automation platform',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'DevOps Tool',
                'tech_stack' => '["Go","Docker","Kubernetes"]',
                'repository_url' => 'https://github.com/example/AutoDeploy',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/autodeploy.png',
                'start_date' => '2025-07-15 00:00:00',
                'completed_date' => null,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'VoiceAssistant',
                'codename' => 'Project Echo',
                'description' => 'Customizable voice assistant for smart homes',
                'status' => 'COMPLETED',
                'project_type' => 'IoT Application',
                'tech_stack' => '["Python","Raspberry Pi","TensorFlow"]',
                'repository_url' => 'https://github.com/example/VoiceAssistant',
                'live_url' => 'https://voice.example.com',
                'cover_image_path' => 'uploads/prototypes/voiceassistant.jpg',
                'start_date' => '2025-06-01 00:00:00',
                'completed_date' => '2025-08-05 00:00:00',
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'CodeReview',
                'codename' => 'Project Inspector',
                'description' => 'AI-powered code review assistant',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'Developer Tool',
                'tech_stack' => '["TypeScript","Next.js","OpenAI API"]',
                'repository_url' => 'https://github.com/example/CodeReview',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/codereview.png',
                'start_date' => '2025-08-10 00:00:00',
                'completed_date' => null,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'EduLearn',
                'codename' => 'Project Mentor',
                'description' => 'Interactive learning platform with adaptive courses',
                'status' => 'PLANNING',
                'project_type' => 'Web Application',
                'tech_stack' => '["Vue.js","Laravel","MySQL"]',
                'repository_url' => null,
                'live_url' => null,
                'cover_image_path' => null,
                'start_date' => '2025-09-15 00:00:00',
                'completed_date' => null,
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TaskFlow',
                'codename' => 'Project Organizer',
                'description' => 'Team task management with automated prioritization',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'Web Application',
                'tech_stack' => '["React","Node.js","MongoDB"]',
                'repository_url' => 'https://github.com/example/TaskFlow',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/taskflow.jpg',
                'start_date' => '2025-07-20 00:00:00',
                'completed_date' => null,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DataViz',
                'codename' => 'Project Insight',
                'description' => 'Advanced data visualization toolkit',
                'status' => 'COMPLETED',
                'project_type' => 'Library',
                'tech_stack' => '["D3.js","TypeScript","Jest"]',
                'repository_url' => 'https://github.com/example/DataViz',
                'live_url' => 'https://dataviz.example.com',
                'cover_image_path' => 'uploads/prototypes/dataviz.png',
                'start_date' => '2025-05-01 00:00:00',
                'completed_date' => '2025-08-01 00:00:00',
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'BlockChain',
                'codename' => 'Project Ledger',
                'description' => 'Custom blockchain implementation for secure transactions',
                'status' => 'IN_DEVELOPMENT',
                'project_type' => 'Blockchain',
                'tech_stack' => '["Rust","Web3.js","Solidity"]',
                'repository_url' => 'https://github.com/example/BlockChain',
                'live_url' => null,
                'cover_image_path' => 'uploads/prototypes/blockchain.jpg',
                'start_date' => '2025-08-05 00:00:00',
                'completed_date' => null,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('prototypes')->insert($projects);
    }
}