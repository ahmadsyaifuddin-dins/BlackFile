<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'category',
        'category_other', 
        'is_public',
        'is_shared',
        'has_ad',
        'public_token',
        'file_path',
        'mime_type',
        'size',
        'links',
        'preview_image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'links' => 'array', // Otomatis mengubah JSON dari database menjadi array di PHP
        'is_public' => 'boolean',
        'is_shared' => 'boolean',
        'has_ad' => 'boolean',
    ];

    /**
     * Mendapatkan user yang memiliki arsip ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The tags that belong to the Archive.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

     /**
     * The users that have favorited this archive.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'archive_user', 'archive_id', 'user_id');
    }

    /**
     * Link publik EKSTERNAL untuk berbagi ke luar sistem (/s/{token}).
     *
     * Hanya tersedia kalau dua-duanya AKTIF:
     * - is_public (visibilitas internal antar user sistem)
     * - is_shared (link eksternal dibagikan ke publik)
     *
     * Kalau is_public internal mati (private), link eksternal "diam"/tidak
     * berfungsi, sejalan dengan aturan keamanan BlackFile.
     */
    public function publicUrl(): ?string
    {
        if (! $this->is_public || ! $this->is_shared || ! $this->public_token) {
            return null;
        }

        return route('archives.public', $this->public_token);
    }

    /**
     * Pastikan archive punya token publik (dipanggil saat pertama kali public).
     */
    public function ensurePublicToken(): void
    {
        if (! $this->public_token) {
            $this->public_token = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(24));
            $this->save();
        }
    }
}