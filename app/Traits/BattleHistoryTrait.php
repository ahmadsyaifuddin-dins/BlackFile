<?php

namespace App\Traits;

use App\Models\BattleHistory;

trait BattleHistoryTrait
{
    /**
     * Menyimpan hasil battle ke database
     */
    protected function logBattleHistory($entA, $entB, $response, $scenarioType)
    {
        // Ambil data dari JsonResponse
        $data = $response->getData();

        BattleHistory::create([
            'attacker_id' => $entA->id,
            'defender_id' => $entB->id,
            'winner_id' => $data->winner_id,
            'win_probability' => $data->win_probability,
            'scenario_type' => $scenarioType,
            'logs' => $data->logs,
        ]);
    }
}
