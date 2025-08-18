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
}