<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiBattleLogger
{
    protected $apiKey;

    // Pakai Gemini gemini-2.0-flash atau gemini-2.5-flash
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    // UPDATED: Tambah parameter $arena
    public function generateLog($winner, $loser, $winReason, $probabilitas, $arena)
    {
        if (empty($this->apiKey)) {
            return ['[SYSTEM ERROR] API Key Missing.'];
        }

        // PROMPT YANG LEBIH PINTAR & SADAR LINGKUNGAN
        $prompt = "
        Bertindaklah sebagai 'BlackFile Combat AI'.
        Tugas: Buat log narasi pertempuran sinematik (maks 5 baris) dalam Bahasa Indonesia.

        Data Pertempuran:
        - Lokasi Arena: $arena (PENTING: Masukkan unsur lokasi ini ke dalam cerita!)
        - Pemenang: $winner
        - Kalah: $loser
        - Faktor Menang: $winReason (Probabilitas: $probabilitas%)

        INSTRUKSI KHUSUS:
        1. HANYA berikan log output. JANGAN ada kata pengantar seperti 'Baik', 'Berikut', 'Siap'. LANGSUNG [PREFIX].
        2. JIKA ada teks `Script Override`, tulis ulang kodenya persis.
        3. JIKA ada teks `Lore Correction` atau `Data Corruption`, ceritakan bahwa pertarungan ini adalah sebuah anomali atau kesalahpahaman sejarah.
        4. Sesuaikan efek serangan dengan Lokasi Arena. 
           - Jika OCEANIC -> Gunakan air, ombak, tekanan, kedalaman.
           - Jika INFERNAL -> Gunakan api, lahar, panas.
           - Jika VOID -> Gunakan hampa udara, gravitasi nol, dingin.
           - Jika URBAN -> Gunakan bangunan, gedung, kota-kota, jalan, lalu lintas.

        5. Gunakan kata kerja yang brutal dan destruktif.
        Contoh Format (Jangan copy isinya, ikuti gayanya):
        [DETECT] Target terkunci di sektor $arena. $winner menginisiasi serbuan.
        [CLASH] Benturan keras mengguncang area. Pertahanan $loser retak.
        [CRITICAL] $winner memanfaatkan medan tempur untuk serangan mematikan.
        [FATALITY] $loser hancur tak bersisa oleh $winReason.
        [SYSTEM] Ancaman musnah. Dominasi area dikonfirmasi ($probabilitas%).
        ";

        try {
            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}?key={$this->apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.8,
                        'maxOutputTokens' => 1000,
                    ],
                ]);

            if ($response->successful()) {
                $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    return array_values(array_filter(explode("\n", trim($text))));
                }
            }

            // Error handling sederhana
            return ['[SYSTEM FAILURE] AI Narrative Module Offline.'];

        } catch (\Exception $e) {
            return ['[SYSTEM EXCEPTION] Connection Error.'];
        }
    }
}
