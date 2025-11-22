<?php

namespace App\Http\Controllers;

use App\Models\BattleHistory;
use App\Models\Entity;
use App\Services\GeminiBattleLogger;
use App\Traits\BattleCalculatorTrait;
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
            ->take(7)
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

        // Logs Header (Ini log sistem awal, kita simpan)
        $systemLogs = [];
        $systemLogs[] = 'INITIALIZING BATTLE SIMULATION PROTOCOL...';
        $systemLogs[] = "SUBJECT A: [{$entA->name}] - TIER {$entA->power_tier} ({$entA->combat_type})";
        $systemLogs[] = "SUBJECT B: [{$entB->name}] - TIER {$entB->power_tier} ({$entB->combat_type})";
        $systemLogs[] = "LOADING ENVIRONMENTAL PARAMETERS... [{$arena}]";

        $response = null;
        $scenarioType = 'COMBAT_DUEL';

        // --- 1. EKSEKUSI LOGIKA PERTARUNGAN (Traits) ---

        // 1.a Cek Lore
        $specialResponse = $this->checkSpecialInteractions($entA, $entB, $systemLogs);
        if ($specialResponse) {
            $response = $specialResponse;
            $scenarioType = 'LORE_EVENT';
        }
        // 1.b Cek Absolute Tier
        elseif (($entA->power_tier === 1 && $entB->power_tier > 1) || ($tierGap = $entB->power_tier - $entA->power_tier) >= 2) {
            $response = $this->absoluteWin($entA, $entB, $systemLogs, 'DIMENSIONAL SUPERIORITY');
            $scenarioType = 'ABSOLUTE_STOMP';
        } elseif (($entB->power_tier === 1 && $entA->power_tier > 1) || $tierGap <= -2) {
            $response = $this->absoluteWin($entB, $entA, $systemLogs, 'DIMENSIONAL SUPERIORITY');
            $scenarioType = 'ABSOLUTE_STOMP';
        }
        // 1.c Battle Standard
        if (! $response) {
            $systemLogs[] = 'TIER DISPARITY NOMINAL. ENGAGING COMBAT CALCULATION.';
            if ($entA->combat_type === 'HAZARD' || $entB->combat_type === 'HAZARD') {
                $scenarioType = 'HAZARD_TEST';
                $response = $this->hazardSimulation($entA, $entB, $systemLogs, $arena);
            } else {
                $scenarioType = 'COMBAT_DUEL';
                $response = $this->combatSimulation($entA, $entB, $systemLogs, $arena);
            }
        }

        // --- 2. INJEKSI GEMINI AI (HYBRID LOGIC) ---
        // Kita bongkar response JSON dari traits, lalu kita suntikkan cerita AI

        if ($response && $response->status() == 200) {
            $originalData = $response->getData(true); // Convert JSON ke Array

            // Pastikan ada pemenang (Bukan Stalemate/Seri) untuk digenerate ceritanya
            if (isset($originalData['winner_id']) && $originalData['winner_id'] !== null) {

                $winnerName = $originalData['winner_name'];
                // Cari nama yang kalah
                $loserName = ($entA->name === $winnerName) ? $entB->name : $entA->name;
                $reason = $originalData['reason'] ?? 'Tactical Superiority';
                $prob = $originalData['win_probability'] ?? 50;

                // Panggil Service Gemini
                try {
                    $aiLogger = new GeminiBattleLogger;
                    $aiLogs = $aiLogger->generateLog($winnerName, $loserName, $reason, $prob, $arena);
                    // PENGGABUNGAN LOG:
                    // 1. Ambil 4 baris pertama log sistem (Header/Init) agar terlihat teknis
                    // 2. Masukkan Log AI (Cerita pertarungan)
                    // 3. Masukkan Reason di akhir

                    // Ambil log sistem murni (bukan hasil generik trait)
                    // Kita ambil 4-5 baris pertama dari $originalData['logs'] yang biasanya berisi Init data
                    $headerLogs = array_slice($originalData['logs'], 0, 5);

                    // Gabungkan
                    $finalLogs = array_merge($headerLogs, $aiLogs);

                    // Update data response
                    $originalData['logs'] = $finalLogs;

                    // Re-create Response dengan data baru
                    $response = response()->json($originalData);

                } catch (\Exception $e) {
                    // Jika AI Error, biarkan pakai log default dari Trait
                    // Tidak perlu melakukan apa-apa, $response tetap yang lama
                }
            }
        }

        // --- 3. SIMPAN KE DB ---
        // Catatan: Kita simpan $response final (yang mungkin sudah ada log AI-nya)
        // Namun logic `logBattleHistory` kamu di trait mungkin perlu response object

        $this->logBattleHistory($entA, $entB, $response, $scenarioType);

        return $response;
    }
}
