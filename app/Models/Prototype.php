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

    $validStatuses = ['COMPLETED', 'ON_HOLD', 'ARCHIVED'];
    if (is_null($days) || !in_array($this->status, $validStatuses)) {
        return null;
    }

    // [PERBAIKAN] Kembalikan KUNCI terjemahan, bukan teks langsung
    return match (true) {
        $days <= 3   => ['name' => 'achievements.blitz_operation', 'tier' => 'legendary'],
        $days <= 5   => ['name' => 'achievements.rapid_deployment', 'tier' => 'legendary'],
        $days <= 7   => ['name' => 'achievements.efficient_execution', 'tier' => 'excellent'],
        $days <= 12  => ['name' => 'achievements.calculated_advance', 'tier' => 'excellent'],
        $days <= 20  => ['name' => 'achievements.sustained_effort', 'tier' => 'standard'],
        $days <= 31  => ['name' => 'achievements.month_long_campaign', 'tier' => 'standard'],
        $days <= 62  => ['name' => 'achievements.strategic_siege', 'tier' => 'longterm'],
        $days <= 93  => ['name' => 'achievements.generational_strategy', 'tier' => 'longterm'],
        default      => ['name' => 'achievements.the_grand_saga', 'tier' => 'epic'],
    };
    }
}