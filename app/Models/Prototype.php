<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prototype extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tech_stack' => 'array',
        'start_date' => 'datetime:Y-m-d\TH:i:s',
        'completed_date' => 'datetime:Y-m-d\TH:i:s',
    ];

    /**
     * Mendapatkan user (operative) yang memiliki berkas prototipe ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [BARU] Accessor untuk menghitung durasi proyek dalam hari.
     * Bisa diakses di Blade dengan: $prototype->duration_in_days
     */
    public function getDurationInDaysAttribute(): ?int
    {
        // Pastikan kedua tanggal ada untuk bisa dihitung
        if ($this->start_date && $this->completed_date) {
            // Gunakan fungsi bawaan Carbon untuk menghitung selisih hari
            return $this->start_date->diffInDays($this->completed_date);
        }

        return null; // Kembalikan null jika salah satu tanggal tidak ada
    }

    /**
     * [BARU] Accessor untuk menentukan nama achievement berdasarkan durasi.
     * Bisa diakses di Blade dengan: $prototype->achievement
     */
    public function getAchievementAttribute(): ?array
    {
        $days = $this->duration_in_days;
    
        // [PERBAIKAN] Cek apakah status adalah salah satu dari tiga ini
        $validStatuses = ['COMPLETED', 'ON_HOLD', 'ARCHIVED'];
        if (is_null($days) || !in_array($this->status, $validStatuses)) {
            return null;
        }
    
        // [PERBAIKAN] Kembalikan nama dan TIER, bukan kelas spesifik
        return match (true) {
            // Tier Legendaris / Sangat Cepat
            $days <= 3   => ['name' => 'Blitz Operation',       'tier' => 'legendary'],
            $days <= 5   => ['name' => 'Rapid Deployment',      'tier' => 'legendary'],
            
            // Tier Unggul / Cepat
            $days <= 7   => ['name' => 'Efficient Execution',   'tier' => 'excellent'],
            $days <= 12  => ['name' => 'Calculated Advance',    'tier' => 'excellent'],
            
            // Tier Standar / Efektif
            $days <= 20  => ['name' => 'Sustained Effort',      'tier' => 'standard'],
            $days <= 31  => ['name' => 'Month-Long Campaign',   'tier' => 'standard'],
            
            // Tier Jangka Panjang / Maraton
            $days <= 62  => ['name' => 'Strategic Siege',       'tier' => 'longterm'],
            $days <= 93  => ['name' => 'Generational Strategy', 'tier' => 'longterm'],
            
            // Tier Epik / Sangat Lama
            default      => ['name' => 'The Grand Saga',        'tier' => 'epic'],
        };
    }
}