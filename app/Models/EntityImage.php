<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',
        'path',
        'caption',
    ];

    /**
     * Get the entity that owns the image.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}