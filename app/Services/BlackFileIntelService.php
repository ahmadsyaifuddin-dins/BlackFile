<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlackFileIntelService
{
    protected $apiKey;

    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY_STATS');
        // Kita gunakan Gemini 2.0 Flash karena cepat dan cerdas untuk logic instruksi
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
    }

    /**
     * Generate Tactical Assessment untuk Entitas
     */
    public function generateAssessment($name, $description, $abilities, $origin, $category)
    {
        // 1. Definisikan Rulebook BlackFile ke dalam Prompt
        // Kita menyuapkan dokumentasi langsung ke AI agar dia paham konteksnya.
        $systemPrompt = <<<EOT
            Kamu adalah Chief Tactical AI untuk agensi intelijen "BlackFile".
            Tugasmu adalah menganalisis entitas/anomali dan membuat "Tactical Assessment" berdasarkan aturan ketat.
                
            ### INPUT DATA:
            - Nama: $name
            - Kategori: $category
            - Asal: $origin
            - Description: $description
            - Abilities: $abilities
                
            ### ATURAN PENILAIAN (RULES):
                
            1. **POWER TIER (Inverse Scale 0-9):**
               - 0 = BOUNDLESS (Pencipta/Tuhan)
               - 1 = TRANS-DIMENSIONAL (Multiverse, Archangel Michael/Lucifer)
               - 2 = PLANETARY / COSMIC (Dewa, Penghancur Planet)
               - 3 = CONTINENTAL (Ancaman Benua, Kaiju, Negara)
               - 4 = CATASTROPHE (Ancaman Kota, SCP Keter Kuat)
               - 5 = DESTRUCTIVE (Level Gedung, Iron Man/Hulk)
               - 6 = SUPERHUMAN (Captain America)
               - 7 = PEAK HUMAN (John Wick, Pasukan Khusus)
               - 8 = HUMAN (Manusia Biasa/Prajurit)
               - 9 = SUB-HUMAN (Lemah/Hewan Kecil)
               - 10 = HARMLESS (Tidak berbahaya)
               *NOTE: Be accurate. Do not overrate human-level characters.*
                
            2. **COMBAT TYPE:** Pilih SATU: [AGGRESSOR, MYSTIC, HAZARD, DIVINE, COSMIC, TACTICAL].
               - AGGRESSOR: Fisik/Melee.
               - MYSTIC: Sihir/Energi.
               - HAZARD: Bahaya pasif (Racun, Radiasi, Virus).
               - DIVINE: Malaikat/Iblis/Entitas Suci.
               - COSMIC: Alien/Eldritch.
               - TACTICAL: Teknologi/Strategi/Senjata Api.
                
            3. **STATS (0-100):** Isi strength, speed, durability, intelligence, energy, combat_skill dengan angka logis.
               - strength (STR): Physical lifting/striking power.
               - speed (SPD): Movement/Reaction.
               - durability (DUR): Toughness/Regeneration.
               - intelligence (INT): IQ, Strategy, Tactic.
               - energy (NRG): Magic, Mana, Ki, or Ranged Firepower.
               - combat_skill (CBT): Martial arts, Weapon mastery.
                
            ### OUTPUT FORMAT (JSON ONLY):
            Kembalikan JSON valid saja. 
            PENTING: Field "reasoning" WAJIB menggunakan BAHASA INDONESIA yang taktis, singkat, dan padat.
            Structure:
            {
                "power_tier": (integer 0-9),
                "combat_type": (string),
                "combat_stats": {
                    "strength": (int),
                    "speed": (int),
                    "durability": (int),
                    "intelligence": (int),
                    "energy": (int),
                    "combat_skill": (int)
                },
                "reasoning": "Jelaskan alasan penilaian tier dalam 1 kalimat Bahasa Indonesia."
            }
            EOT;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.4, // Rendah agar konsisten dan tidak halusinasi
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                    'responseMimeType' => 'application/json', // Memaksa output JSON
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ambil text dari response Gemini
                $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

                // Bersihkan jika ada markdown wrapping (meskipun sudah dipaksa JSON)
                $cleanJson = str_replace(['```json', '```'], '', $rawText);

                return json_decode($cleanJson, true);
            } else {
                Log::error('Gemini API Error ['.$response->status().']: '.$response->body());

                return null;
            }

        } catch (\Exception $e) {
            Log::error('BlackFile Intel Service Exception: '.$e->getMessage());

            return null;
        }
    }
}
