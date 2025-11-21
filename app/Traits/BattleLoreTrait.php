<?php

namespace App\Traits;

trait BattleLoreTrait
{
    /**
     * Mengecek apakah ada interaksi spesial antar karakter (Easter Eggs)
     */
    protected function checkSpecialInteractions($entA, $entB, &$logs)
    {
        $nameA = strtolower($entA->name);
        $nameB = strtolower($entB->name);

        // Helper: Cek match bolak-balik
        $isMatch = fn ($n1, $n2) => ($nameA === $n1 && $nameB === $n2) || ($nameA === $n2 && $nameB === $n1);

        // 1. MICHAEL VS LUCIFER
        if (str_contains($nameA, 'michael') && str_contains($nameB, 'lucifer') || str_contains($nameB, 'michael') && str_contains($nameA, 'lucifer')) {
            $winner = str_contains($nameA, 'michael') ? $entA : $entB;

            $logs[] = 'WARNING: HIGH-LEVEL ESCHATOLOGICAL EVENT DETECTED.';
            $logs[] = 'Matching signatures: [ARCHANGEL_COMMANDER] vs [THE_FALLEN_ONE].';
            $logs[] = "Script override: 'REVELATION_12:7' executed.";
            $logs[] = 'Dimensional stability compromised. Reality trembling...';
            $logs[] = 'Result: The Light prevails over Darkness.';

            return response()->json([
                'winner_id' => $winner->id,
                'winner_name' => $winner->name,
                'win_probability' => 100,
                'logs' => $logs,
                'reason' => "Prophetic Outcome: 'And there was war in heaven: Michael and his angels fought against the dragon.'",
            ]);
        }

        // 2. SCP-682 VS SCP-999
        if ($isMatch('scp-682', 'scp-999')) {
            $logs[] = 'Interaction Protocol: HARD-TO-DESTROY REPTILE vs TICKLE MONSTER.';
            $logs[] = 'Subject [SCP-999] embraces Subject [SCP-682].';
            $logs[] = 'Aggression levels dropping to 0%. Serotonin levels critical.';
            $logs[] = 'Subject [SCP-682] is... laughing?';

            $winner = str_contains($nameA, '999') ? $entA : $entB;

            return response()->json([
                'winner_id' => $winner->id,
                'winner_name' => $winner->name,
                'win_probability' => 100,
                'logs' => $logs,
                'reason' => 'Pacification Successful. Target rendered docile via euphoria.',
            ]);
        }

        // 3. SCP-173 VS SCP-096
        if ($isMatch('scp-173', 'scp-096')) {
            $logs[] = 'Paradox Detected: Visual-Dependent Aggressors.';
            $logs[] = 'Infinite Loop: 096 attacks invincible stone -> 173 frozen in place.';

            return response()->json([
                'winner_id' => null,
                'winner_name' => 'STALEMATE (SERI)',
                'win_probability' => 50,
                'logs' => $logs,
                'reason' => 'Eternal Stalemate. Neither entity can effectively terminate the other.',
            ]);
        }

        // 4. HOLY PURGE (Angel vs Demon Generic)
        $catA = $entA->category;
        $catB = $entB->category;

        $isAngel = fn ($cat) => str_contains($cat, 'Angelic');
        $isDemon = fn ($cat) => str_contains($cat, 'Demonic');

        if (($isAngel($catA) && $isDemon($catB)) || ($isAngel($catB) && $isDemon($catA))) {
            $angel = $isAngel($catA) ? $entA : $entB;
            $demon = $isAngel($catA) ? $entB : $entA;

            $logs[] = 'DETECTING POLARITY CLASH: HOLY VS UNHOLY.';
            $logs[] = "Subject [{$angel->name}] manifests divine radiance.";

            // --- LOGIKA BARU: DIVINE AUTHORITY ---
            // Cek apakah Malaikatnya adalah MICHAEL atau GABRIEL (Archangel Utama)
            $isArchangelCommander = str_contains(strtolower($angel->name), 'michael') ||
                                    str_contains(strtolower($angel->name), 'gabriel');

            // Jika itu Michael/Gabriel, ATAU jika Tier Malaikat lebih tinggi (angka lebih kecil)
            if ($isArchangelCommander || $angel->power_tier < $demon->power_tier) {

                if ($isArchangelCommander) {
                    $logs[] = 'Absolute Authority recognized: The Commander of Heavenly Hosts.';
                    $logs[] = 'Environmental factors negated. Demon hierarchy suppressed.';
                }

                $logs[] = 'Divine Judgement executed. Darkness purged.';

                return response()->json([
                    'winner_id' => $angel->id,
                    'winner_name' => $angel->name,
                    'win_probability' => 100, // Mutlak 100%
                    'logs' => $logs,
                    'reason' => "Divine Supremacy. The Creator's authority overrides environmental advantages.",
                ]);
            }

            // Jika Tier SAMA dan BUKAN Michael (Misal Angel biasa vs Demon biasa),
            // baru lanjut ke fisik/arena.
            if ($angel->power_tier === $demon->power_tier) {
                $logs[] = 'Power levels equivalent. The conflict escalates to physical manifestation.';
            }
        }

        return null; // Tidak ada event spesial
    }
}
