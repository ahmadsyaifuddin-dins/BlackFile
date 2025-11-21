<?php

namespace App\Http\Controllers;

use App\Models\BattleHistory;
use App\Models\Entity;
use App\Traits\BattleCalculatorTrait;
// Import Traits
use App\Traits\BattleHistoryTrait;
use App\Traits\BattleLoreTrait;
use Illuminate\Http\Request;

class BattleController extends Controller
{
    use BattleCalculatorTrait, BattleHistoryTrait, BattleLoreTrait;

    public function index()
    {
        $entities = Entity::select('id', 'name', 'power_tier', 'thumbnail_image_id', 'combat_type', 'combat_stats')
            ->with(['thumbnail', 'images'])
            ->orderBy('name')
            ->get();

        $history = BattleHistory::with(['attacker', 'defender', 'winner'])
            ->latest()
            ->take(10)
            ->get();

        return view('battle.index', compact('entities', 'history'));
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'attacker_id' => 'required|exists:entities,id',
            'defender_id' => 'required|exists:entities,id|different:attacker_id',
            'arena' => 'nullable|string|in:NEUTRAL,OCEANIC,INFERNAL,SANCTUARY,URBAN,VOID',
        ]);

        $entA = Entity::findOrFail($request->attacker_id);
        $entB = Entity::findOrFail($request->defender_id);
        $arena = $request->input('arena', 'NEUTRAL');

        $logs = [];
        $logs[] = 'INITIALIZING BATTLE SIMULATION PROTOCOL...';
        $logs[] = "SUBJECT A: [{$entA->name}] - TIER {$entA->power_tier} ({$entA->combat_type})";
        $logs[] = "SUBJECT B: [{$entB->name}] - TIER {$entB->power_tier} ({$entB->combat_type})";
        $logs[] = "LOADING ENVIRONMENTAL PARAMETERS... [{$arena}]";
        $response = null;
        $scenarioType = 'COMBAT_DUEL';

        // 1. CEK LORE / SPECIAL INTERACTION (Dari Trait)
        $specialResponse = $this->checkSpecialInteractions($entA, $entB, $logs);

        if ($specialResponse) {
            $response = $specialResponse;
            $scenarioType = 'LORE_EVENT';
        }

        // 2. CEK ABSOLUTE TIER (Dari Trait)
        elseif (($entA->power_tier === 1 && $entB->power_tier > 1) || ($tierGap = $entB->power_tier - $entA->power_tier) >= 2) {
            $response = $this->absoluteWin($entA, $entB, $logs, 'DIMENSIONAL SUPERIORITY');
            $scenarioType = 'ABSOLUTE_STOMP';
        } elseif (($entB->power_tier === 1 && $entA->power_tier > 1) || $tierGap <= -2) {
            $response = $this->absoluteWin($entB, $entA, $logs, 'DIMENSIONAL SUPERIORITY');
            $scenarioType = 'ABSOLUTE_STOMP';
        }

        // 3. BATTLE STANDARD (Dari Trait)
        if (! $response) {
            $logs[] = 'TIER DISPARITY NOMINAL. ENGAGING COMBAT CALCULATION.';
            if ($entA->combat_type === 'HAZARD' || $entB->combat_type === 'HAZARD') {
                $scenarioType = 'HAZARD_TEST';
                $response = $this->hazardSimulation($entA, $entB, $logs, $arena);
            } else {
                $scenarioType = 'COMBAT_DUEL';
                $response = $this->combatSimulation($entA, $entB, $logs, $arena);
            }
        }

        // 4. SIMPAN KE DB (Dari Trait)
        $this->logBattleHistory($entA, $entB, $response, $scenarioType);

        return $response;
    }
}
