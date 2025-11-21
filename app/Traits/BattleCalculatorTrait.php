<?php

namespace App\Traits;

trait BattleCalculatorTrait
{
    /**
     * Logic Tier Gap / Absolute Stomp
     */
    protected function absoluteWin($winner, $loser, &$logs, $reason)
    {
        $logs[] = 'CRITICAL: DETECTING MASSIVE EXISTENCE GAP.';
        $logs[] = "Subject [{$loser->name}] attempts to engage.";
        $logs[] = "Subject [{$winner->name}] ignores the attempt. Effect is negligible.";
        $logs[] = 'Calculation bypassed. Outcome is deterministic.';

        return response()->json([
            'winner_id' => $winner->id,
            'winner_name' => $winner->name,
            'win_probability' => 100,
            'logs' => $logs,
            'reason' => "Absolute Hierarchy Rule: Tier {$winner->power_tier} entity cannot be harmed by Tier {$loser->power_tier}.",
        ]);
    }

    /**
     * Logic PvP (Aggressor vs Aggressor)
     */
    protected function combatSimulation($entA, $entB, &$logs, $arena = 'NEUTRAL')
    {
        // 1. Ambil Stats Awal
        $sA = $this->getStats($entA);
        $sB = $this->getStats($entB);

        // 2. Terapkan Modifier Arena (DRY Principle)
        $this->applyArenaModifiers($entA, $sA, $logs, $arena);
        $this->applyArenaModifiers($entB, $sB, $logs, $arena);

        // 3. Rumus Hitung Skor
        $calculateScore = function ($stats, $tier) {
            $score = ($stats['str'] * 1.0) + ($stats['spd'] * 1.2) + ($stats['int'] * 1.5) +
                     ($stats['cbt'] * 1.3) + ($stats['nrg'] * 1.1) + ($stats['dur'] * 1.0);

            // Bonus Overwhelming Power
            if ($stats['nrg'] >= 100 || $stats['str'] >= 100) {
                $score += 200;
            }

            // Tier Multiplier
            $tierMultiplier = max(1, (12 - $tier) * 0.5);

            return $score * $tierMultiplier;
        };

        $scoreA = $calculateScore($sA, $entA->power_tier) * (rand(98, 102) / 100);
        $scoreB = $calculateScore($sB, $entB->power_tier) * (rand(98, 102) / 100);

        $logs[] = 'Comparing Combat Metrics...';

        // Narasi Speed
        if ($sA['spd'] > $sB['spd'] + 20) {
            $logs[] = "> [{$entA->name}] moves faster than [{$entB->name}] can perceive.";
        } elseif ($sB['spd'] > $sA['spd'] + 20) {
            $logs[] = "> [{$entB->name}] blitzes [{$entA->name}] with superior speed.";
        } else {
            $logs[] = '> Both subjects clash at comparable velocities.';
        }

        // Narasi Skill
        if ($sA['nrg'] >= 100 && $sA['cbt'] == 0) {
            $logs[] = "> [{$entA->name}] lacks skill, but unleashes OMNIPOTENT ENERGY.";
        }
        if ($sB['nrg'] >= 100 && $sB['cbt'] == 0) {
            $logs[] = "> [{$entB->name}] lacks skill, but unleashes OMNIPOTENT ENERGY.";
        }

        $total = $scoreA + $scoreB;
        $probA = ($total > 0) ? round(($scoreA / $total) * 100) : 50;
        $winner = ($scoreA > $scoreB) ? $entA : $entB;

        $logs[] = 'Simulating 1,000 scenarios...';
        $logs[] = 'Impact Analysis complete.';

        return response()->json([
            'winner_id' => $winner->id,
            'winner_name' => $winner->name,
            'win_probability' => ($winner->id == $entA->id) ? $probA : (100 - $probA),
            'logs' => $logs,
            'reason' => 'Superior Combat Proficiency & Stats Aggregate.',
        ]);
    }

    /**
     * Logic Hazard (Survival Test)
     */
    protected function hazardSimulation($entA, $entB, &$logs, $arena = 'NEUTRAL')
    {
        // 1. Ambil Stats Awal
        $sA = $this->getStats($entA);
        $sB = $this->getStats($entB);

        // 2. Terapkan Modifier Arena (DRY Principle)
        $this->applyArenaModifiers($entA, $sA, $logs, $arena);
        $this->applyArenaModifiers($entB, $sB, $logs, $arena);

        // 3. Tentukan Role & Gunakan Stats yang SUDAH dimodifikasi
        if ($entA->combat_type === 'HAZARD') {
            $hazard = $entA;
            $victim = $entB;
            $hStats = $sA;
            $vStats = $sB; // Pakai $sA/$sB yang sudah kena buff arena
        } else {
            $hazard = $entB;
            $victim = $entA;
            $hStats = $sB;
            $vStats = $sA; // Pakai $sA/$sB yang sudah kena buff arena
        }

        $logs[] = 'SCENARIO TYPE: CONTAINMENT BREACH / HAZARD EXPOSURE.';
        $logs[] = "Subject [{$victim->name}] enters the influence radius of [{$hazard->name}].";

        $survivalScore = 0;
        $difficultyScore = 0;

        // Mental vs Fisik
        if ($hStats['int'] > 50 || $hStats['nrg'] > 50) {
            $logs[] = 'WARNING: Cognitohazard/Memetic agent detected.';
            $logs[] = "Checking Subject [{$victim->name}] Psionic Resistance...";
            $survivalScore = $vStats['int'] * 2;
            $difficultyScore = ($hStats['str'] + $hStats['nrg']);
        } else {
            $logs[] = 'WARNING: Physical/Environmental threat detected.';
            $survivalScore = $vStats['dur'] * 1.5 + $vStats['spd'] * 0.5;
            $difficultyScore = $hStats['str'] * 1.5;
        }

        $isMentalAttack = ($hStats['int'] > 50 || $hStats['nrg'] > 50);

        // Definisi Benda Mati: Durability Tinggi (>60) tapi Intelligence Sangat Rendah (<30)
        // Atau cek nama spesifik
        $isInanimate = ($vStats['dur'] > 60 && $vStats['int'] < 30) ||
                       str_contains(strtolower($victim->name), 'scp-173') ||
                       str_contains(strtolower($victim->name), 'statue') ||
                       str_contains(strtolower($victim->name), 'machine') ||
                       str_contains(strtolower($victim->name), 'robot');

        if ($isMentalAttack && $isInanimate) {
            $logs[] = 'WARNING: Cognitohazard detected.';
            $logs[] = "Scanning Subject [{$victim->name}] consciousness...";
            $logs[] = 'Subject lacks biological mind or soul.';
            $logs[] = 'Hazard effect ineffective against inanimate object.';

            return response()->json([
                'winner_id' => $victim->id,
                'winner_name' => $victim->name,
                'win_probability' => 100,
                'logs' => $logs,
                'reason' => 'Construct Immunity: Target is immune to psychological/soul-based hazards.',
            ]);
        }

        $survivalScore *= (rand(90, 110) / 100);

        if ($survivalScore >= $difficultyScore) {
            $logs[] = '> Subject successfully resisted the anomalous effect.';
            $winner = $victim;
            $winProb = 95;
        } else {
            $logs[] = '> Mental/Physical defenses compromised.';
            $logs[] = "> Subject succumbs to [{$hazard->name}] effects.";
            $winner = $hazard;
            $winProb = 95;
        }

        return response()->json([
            'winner_id' => $winner->id,
            'winner_name' => $winner->name,
            'win_probability' => $winProb,
            'logs' => $logs,
            'reason' => ($winner->id == $hazard->id) ? 'Subject failed to resist Anomalous Hazard properties.' : 'Subject successfully navigated/neutralized the Hazard.',
        ]);
    }

    /**
     * [PRIVATE METHOD] Centralized Arena Logic (DRY)
     * Memodifikasi stats berdasarkan arena secara referensi (&$stats)
     */
    private function applyArenaModifiers($entity, &$stats, &$logs, $arena)
    {
        $name = strtolower($entity->name);
        $origin = strtolower($entity->origin ?? '');
        $cat = strtolower($entity->category);

        $buffMsg = null;
        $debuffMsg = null;

        switch ($arena) {
            case 'OCEANIC':
                if (str_contains($origin, 'ocean') || str_contains($origin, 'sea') || str_contains($cat, 'cthulhu') || str_contains($name, 'leviathan')) {
                    $stats['str'] *= 1.3;
                    $stats['spd'] *= 1.5;
                    $buffMsg = "> ENVIRONMENT BONUS: [{$entity->name}] gains aquatic superiority.";
                } elseif (str_contains($name, 'fire') || str_contains($name, 'human') || $entity->power_tier >= 8) {
                    $stats['spd'] *= 0.5;
                    $debuffMsg = "> ENVIRONMENT PENALTY: [{$entity->name}] struggles in deep pressure.";
                }
                break;

            case 'INFERNAL':
                if (str_contains($cat, 'demon') || str_contains($origin, 'hell')) {
                    $stats['nrg'] *= 1.3;
                    $stats['str'] *= 1.2;
                    $buffMsg = "> ENVIRONMENT BONUS: [{$entity->name}] feeds on hell energy.";
                } elseif (str_contains($cat, 'angel') || str_contains($cat, 'humanoid')) {
                    $stats['dur'] *= 0.8;
                    $debuffMsg = "> ENVIRONMENT PENALTY: [{$entity->name}] takes continuous heat damage.";
                }
                break;

            case 'SANCTUARY':
                if (str_contains($cat, 'angel')) {
                    $stats['nrg'] *= 1.5;
                    $buffMsg = "> ENVIRONMENT BONUS: [{$entity->name}] is empowered by Holy Ground.";
                } elseif (str_contains($cat, 'demon')) {
                    $stats['nrg'] *= 0.5;
                    $stats['dur'] *= 0.7;
                    $debuffMsg = "> ENVIRONMENT PENALTY: Holy ground burns [{$entity->name}].";
                }
                break;

            case 'VOID':
                if (str_contains($cat, 'extraterrestrial') || str_contains($cat, 'cosmic') || str_contains($cat, 'cthulhu')) {
                    $stats['spd'] *= 1.2;
                    $buffMsg = "> ENVIRONMENT BONUS: Zero-G maneuverability active for [{$entity->name}].";
                } elseif ($entity->power_tier >= 8 && str_contains($cat, 'human')) {
                    $stats['dur'] = 0; // Mati beku/habis napas
                    $debuffMsg = "> CRITICAL ENVIRONMENT: [{$entity->name}] cannot survive in vacuum.";
                }
                break;

            case 'URBAN':
                // Bonus untuk entitas street level / urban legend
                if ($entity->power_tier >= 7 && (str_contains($cat, 'human') || str_contains($cat, 'folklore'))) {
                    $stats['spd'] *= 1.1;
                    $stats['int'] *= 1.2; // Lebih banyak tempat sembunyi/taktik
                    $buffMsg = "> ENVIRONMENT BONUS: [{$entity->name}] utilizes urban cover effectively.";
                }
                break;
        }

        if ($buffMsg) {
            $logs[] = $buffMsg;
        }
        if ($debuffMsg) {
            $logs[] = $debuffMsg;
        }
    }

    /**
     * Helper Parse JSON Stats
     */
    private function getStats($entity)
    {
        $stats = $entity->combat_stats ?? [];

        return [
            'str' => $stats['strength'] ?? 0,
            'spd' => $stats['speed'] ?? 0,
            'dur' => $stats['durability'] ?? 0,
            'int' => $stats['intelligence'] ?? 0,
            'nrg' => $stats['energy'] ?? 0,
            'cbt' => $stats['combat_skill'] ?? 0,
        ];
    }
}
