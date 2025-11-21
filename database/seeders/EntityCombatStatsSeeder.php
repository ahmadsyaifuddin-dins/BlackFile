<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntityCombatStatsSeeder extends Seeder
{
    public function run()
    {
        // LIST SPESIFIK UNTUK ENTITAS POWERFUL
        // Sisanya yang tidak ada di sini akan masuk Tier 8 (Default)

        $data = [
            // --- TIER 1: OUTER GODS / OMNIPOTENT CONCEPTS ---
            'Azathoth' => ['tier' => 1, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 0, 'durability' => 100, 'intelligence' => 0, 'energy' => 100, 'combat_skill' => 0]],

            // --- TIER 2: ARCHANGELS & HIERARKI SURGA (ABSOLUTE) ---
            // Seraphim, Cherubim, Ophanim, Michael, Gabriel, dll.
            'Seraphim' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 0]], // Pure Energy/Holy Fire
            'Cherubim' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 100, 'durability' => 100, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 80]],
            'Ophanim' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 0]], // Roda Mata
            'Michael' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 95, 'energy' => 100, 'combat_skill' => 100]],
            'Gabriel' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 100, 'durability' => 95, 'intelligence' => 100, 'energy' => 98, 'combat_skill' => 85]],
            'Metatron' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 100, 'durability' => 100, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 80]],
            'Raphael' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 85, 'speed' => 95, 'durability' => 95, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 70]],
            'Uriel' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 95, 'durability' => 95, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 90]],
            'Azrael' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 90, 'energy' => 100, 'combat_skill' => 100]], // Malaikat Maut
            'Israfil' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 90, 'energy' => 100, 'combat_skill' => 50]],

            // --- TIER 2/3: PRINCES OF HELL (DEMON LORDS) ---
            // Mereka sangat kuat, tapi secara lore Archangel (Tier 2) biasanya menang melawan mereka.
            // Kita taruh di Tier 2 (bisa lawan Malaikat) atau Tier 3 (Kalah mutlak sama Tier 2)
            // Untuk keseimbangan game, kita taruh Tier 2 tapi stats sedikit di bawah Malaikat Utama.
            'Lucifer' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 98, 'speed' => 100, 'durability' => 98, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 95]],
            'Satan' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 90, 'durability' => 95, 'intelligence' => 70, 'energy' => 95, 'combat_skill' => 90]],
            'Beelzebub' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 85, 'durability' => 95, 'intelligence' => 90, 'energy' => 90, 'combat_skill' => 80]],
            'Asmodeus' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 85, 'speed' => 80, 'durability' => 90, 'intelligence' => 95, 'energy' => 85, 'combat_skill' => 85]], // Prince of Lust
            'Leviathan' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 90, 'durability' => 100, 'intelligence' => 80, 'energy' => 90, 'combat_skill' => 70]],
            'Mammon' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 70, 'speed' => 60, 'durability' => 80, 'intelligence' => 100, 'energy' => 80, 'combat_skill' => 50]], // Lebih ke ekonomi
            'Belphegor' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 80, 'speed' => 50, 'durability' => 85, 'intelligence' => 100, 'energy' => 85, 'combat_skill' => 60]],
            'Belial' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 90, 'durability' => 90, 'intelligence' => 100, 'energy' => 90, 'combat_skill' => 85]],
            'Astaroth' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 80, 'speed' => 90, 'durability' => 85, 'intelligence' => 100, 'energy' => 100, 'combat_skill' => 80]],
            'Baal' => ['tier' => 2, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 85, 'durability' => 90, 'intelligence' => 90, 'energy' => 90, 'combat_skill' => 90]],
            'Berith' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 75, 'speed' => 80, 'durability' => 80, 'intelligence' => 100, 'energy' => 90, 'combat_skill' => 70]],

            // --- TIER 3: MYTHOLOGICAL GODS & TITANS ---
            'SCP-2317' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 40, 'durability' => 100, 'intelligence' => 80, 'energy' => 90, 'combat_skill' => 50]],
            'SCP-001' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 100, 'durability' => 100, 'intelligence' => 80, 'energy' => 100, 'combat_skill' => 90]], // Gate Guardian
            'Fenrir' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 98, 'speed' => 90, 'durability' => 95, 'intelligence' => 60, 'energy' => 70, 'combat_skill' => 85]],
            'Yamata no Orochi' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 60, 'durability' => 90, 'intelligence' => 50, 'energy' => 85, 'combat_skill' => 70]],
            'Cthulhu' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 50, 'durability' => 98, 'intelligence' => 90, 'energy' => 95, 'combat_skill' => 40]],
            'Anubis' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 85, 'speed' => 90, 'durability' => 90, 'intelligence' => 90, 'energy' => 95, 'combat_skill' => 85]],
            'Prometheus' => ['tier' => 3, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 80, 'durability' => 100, 'intelligence' => 100, 'energy' => 90, 'combat_skill' => 70]],

            // --- TIER 4: KETER / CATASTROPHE ---
            'SCP-682' => ['tier' => 4, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 70, 'durability' => 100, 'intelligence' => 85, 'energy' => 10, 'combat_skill' => 60]],
            'SCP-106' => ['tier' => 4, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 80, 'speed' => 40, 'durability' => 90, 'intelligence' => 85, 'energy' => 80, 'combat_skill' => 70]],
            'SCP-169' => ['tier' => 4, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 100, 'speed' => 5, 'durability' => 100, 'intelligence' => 10, 'energy' => 20, 'combat_skill' => 0]],
            'SCP-096' => ['tier' => 4, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 95, 'speed' => 95, 'durability' => 90, 'intelligence' => 10, 'energy' => 0, 'combat_skill' => 40]],
            'SCP-3000' => ['tier' => 4, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 90, 'speed' => 20, 'durability' => 95, 'intelligence' => 60, 'energy' => 80, 'combat_skill' => 0]], // Anantashesha
            'SCP-1983' => ['tier' => 4, 'type' => 'HAZARD',    'stats' => ['strength' => 90, 'speed' => 80, 'durability' => 100, 'intelligence' => 0, 'energy' => 90, 'combat_skill' => 0]], // Doorway to Nowhere (Hive)
            'SCP-610' => ['tier' => 4, 'type' => 'HAZARD',    'stats' => ['strength' => 80, 'speed' => 40, 'durability' => 80, 'intelligence' => 50, 'energy' => 60, 'combat_skill' => 30]], // Flesh that Hates

            // --- TIER 5: EUCLID / DANGEROUS ---
            'SCP-173' => ['tier' => 5, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 80, 'speed' => 100, 'durability' => 85, 'intelligence' => 20, 'energy' => 0, 'combat_skill' => 50]],
            'SCP-049' => ['tier' => 5, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 40, 'speed' => 50, 'durability' => 50, 'intelligence' => 95, 'energy' => 80, 'combat_skill' => 40]],
            'SCP-939' => ['tier' => 5, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 70, 'speed' => 75, 'durability' => 60, 'intelligence' => 40, 'energy' => 0, 'combat_skill' => 70]],
            'Kuchisake-onna' => ['tier' => 5, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 60, 'speed' => 80, 'durability' => 40, 'intelligence' => 50, 'energy' => 60, 'combat_skill' => 75]],
            'SCP-012' => ['tier' => 5, 'type' => 'HAZARD',    'stats' => ['strength' => 95, 'speed' => 100, 'durability' => 5, 'intelligence' => 0, 'energy' => 80, 'combat_skill' => 0]], // Kertas Musik
            'SCP-002' => ['tier' => 5, 'type' => 'HAZARD',    'stats' => ['strength' => 80, 'speed' => 10, 'durability' => 90, 'intelligence' => 20, 'energy' => 70, 'combat_skill' => 0]],
            'SCP-3008' => ['tier' => 5, 'type' => 'HAZARD',    'stats' => ['strength' => 60, 'speed' => 0, 'durability' => 100, 'intelligence' => 0, 'energy' => 50, 'combat_skill' => 0]],
            'SCP-966' => ['tier' => 5, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 40, 'speed' => 80, 'durability' => 30, 'intelligence' => 60, 'energy' => 50, 'combat_skill' => 50]], // Sleep Killer (Invisible)

            // --- TIER 6-9: MODERATE / LOW / FOLKLORE ---
            'SCP-294' => ['tier' => 6, 'type' => 'HAZARD',    'stats' => ['strength' => 80, 'speed' => 10, 'durability' => 40, 'intelligence' => 0, 'energy' => 90, 'combat_skill' => 0]],
            'Sasquatch' => ['tier' => 7, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 65, 'speed' => 50, 'durability' => 60, 'intelligence' => 40, 'energy' => 0, 'combat_skill' => 40]],
            'The Greys' => ['tier' => 8, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 20, 'speed' => 30, 'durability' => 20, 'intelligence' => 95, 'energy' => 80, 'combat_skill' => 10]],
            'Reptilian' => ['tier' => 6, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 70, 'speed' => 60, 'durability' => 70, 'intelligence' => 90, 'energy' => 50, 'combat_skill' => 60]],
            'SCP-999' => ['tier' => 9, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 10, 'speed' => 30, 'durability' => 90, 'intelligence' => 50, 'energy' => 100, 'combat_skill' => 0]],
            'Banshee' => ['tier' => 6, 'type' => 'HAZARD',    'stats' => ['strength' => 10, 'speed' => 80, 'durability' => 0, 'intelligence' => 60, 'energy' => 80, 'combat_skill' => 0]], // Suara maut

            // --- TIER 10: HUMAN ---
            'Manusia' => ['tier' => 10, 'type' => 'AGGRESSOR', 'stats' => ['strength' => 15, 'speed' => 15, 'durability' => 15, 'intelligence' => 60, 'energy' => 0, 'combat_skill' => 20]],
        ];

        foreach ($data as $name => $values) {
            // Menggunakan LIKE agar cocok dengan nama sebagian (misal "The Burning Ones" cocok dengan "Seraphim" di mapping atau sebaliknya)
            // Tapi lebih aman kita cari berdasarkan string yang ada di $name

            $entities = Entity::where('name', 'LIKE', "%$name%")
                ->orWhere('codename', 'LIKE', "%$name%")
                ->get();

            foreach ($entities as $entity) {
                $entity->update([
                    'power_tier' => $values['tier'],
                    'combat_type' => $values['type'],
                    'combat_stats' => $values['stats'],
                ]);
                $this->command->info('Updated: '.$entity->name.' -> Tier '.$values['tier']);
            }
        }

        // Set default hanya untuk yang BENAR-BENAR sisa (belum punya tier)
        // Kali ini kita set default ke Tier 8 agar entitas baru tidak error, tapi entitas yang dikenal di atas sudah aman.
        $others = Entity::whereNull('combat_stats')->get();
        foreach ($others as $ent) {
            $ent->update([
                'power_tier' => 8,
                'combat_type' => 'AGGRESSOR',
                'combat_stats' => ['strength' => 50, 'speed' => 50, 'durability' => 50, 'intelligence' => 50, 'energy' => 50, 'combat_skill' => 50],
            ]);
        }
    }
}
