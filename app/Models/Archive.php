<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archive extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'is_public', // <-- Tambahkan ini
        'file_path',
        'mime_type',
        'size',
        'links',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'links' => 'array', // Otomatis mengubah JSON dari database menjadi array di PHP
        'is_public' => 'boolean', // <-- Tambahkan ini
    ];

    /**
     * Mendapatkan user yang memiliki arsip ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}