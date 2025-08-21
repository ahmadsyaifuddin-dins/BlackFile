<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncryptedContact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'codename',
        'encrypted_payload',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // INI ADALAH KUNCINYA!
        // Laravel akan secara otomatis mengenkripsi data array ke dalam kolom ini
        // dan mendekripsinya kembali menjadi array saat kita mengambilnya.
        'encrypted_payload' => 'encrypted:array',
    ];

    /**
     * Mendapatkan pengguna (agen) yang memiliki kontak ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
