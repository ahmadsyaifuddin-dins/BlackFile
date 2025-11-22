<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BattleHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'logs' => 'array', // Agar otomatis jadi array saat diambil
        'created_at' => 'datetime',
    ];

    public function attacker(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'attacker_id');
    }

    public function defender(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'defender_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'winner_id');
    }
}
