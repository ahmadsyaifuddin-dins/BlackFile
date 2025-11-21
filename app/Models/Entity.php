<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $casts = [
        'combat_stats' => 'array',
    ];

    protected $fillable = [
        'name',
        'codename',
        'category',
        'rank',
        'origin',
        'description',
        'abilities',
        'weaknesses',
        'status',
        'thumbnail_image_id',
        'power_tier',
        'combat_type',
        'combat_stats',
    ];

    /**
     * Get all of the images for the Entity.
     */
    public function images(): HasMany
    {
        return $this->hasMany(EntityImage::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(EntityImage::class, 'thumbnail_image_id');
    }

    /**
     * Accessor untuk mendapatkan class warna CSS berdasarkan status.
     * Cara panggil di blade: $entity->status_style
     */
    protected function statusStyle(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                // Kategori: AKTIF & SIAP (Hijau/Cyan)
                'ACTIVE' => 'text-green-500 border-green-500',
                'STANDBY' => 'text-cyan-500 border-cyan-500',

                // Kategori: TERKENDALI (Kuning/Amber)
                'CONTAINED' => 'text-yellow-500 border-yellow-500',
                'SEALED' => 'text-amber-500 border-amber-500',

                // Kategori: BAHAYA / KRITIS (Merah/Oranye)
                'UNCONTAINED' => 'text-red-600 border-red-600',
                'FAILING' => 'text-orange-500 border-orange-500',

                // Kategori: NON-AKTIF (Abu-abu)
                'NEUTRALIZED' => 'text-gray-500 border-gray-500',
                'TERMINATED' => 'text-zinc-600 border-zinc-600',

                // Kategori: MISTERIUS (Ungu/Biru)
                'MIA' => 'text-purple-500 border-purple-500',
                'UNKNOWN' => 'text-indigo-400 border-indigo-400',
                'DORMANT' => 'text-blue-800 border-blue-800',

                // Default
                default => 'text-secondary border-secondary',
            }
        );
    }
}
