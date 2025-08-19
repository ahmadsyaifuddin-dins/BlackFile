<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

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
    ];

    /**
     * Get all of the images for the Entity.
     */
    public function images(): HasMany
    {
        return $this->hasMany(EntityImage::class);
    }
}