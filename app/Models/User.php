<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Connection;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'codename',
        'email',
        'password',
        'role_id',
        'avatar',
        'specialization',
        'quotes',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'last_active_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Koneksi di mana user ini adalah SUMBER.
     */
    public function connections()
    {
        return $this->morphMany(Connection::class, 'source');
    }

    /**
     * Mendapatkan semua koneksi di mana user ini adalah TARGET.
     */
    public function connectionsAsTarget()
    {
        return $this->morphMany(Connection::class, 'target');
    }


    /**
     * Mendapatkan semua TARGET (bisa User atau Friend) yang terhubung DARI user ini.
     */
    public function targets()
    {
        return $this->hasManyThrough(
            'App\Models\Friend', // Model akhir yang ingin diakses (contoh)
            'App\Models\Connection', // Model perantara
            'source_id', // Foreign key di tabel connections
            'id', // Local key di tabel friends
            'id', // Local key di tabel users
            'target_id' // Foreign key di tabel connections
        )->where('connections.source_type', User::class);
    }

    /**
     * Relasi untuk mendapatkan SEMUA level bawahan secara rekursif.
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }


    public function friends()
    {
        return $this->hasMany(Friend::class);
    }


    /**
     * [BARU & MEMPERBAIKI MASALAH]
     * Accessor cerdas untuk mendapatkan "parent" atau "handler" dari tabel connections.
     * Ini akan dipanggil secara otomatis saat kita menulis `$user->parent`.
     */
    protected function parent(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Cari koneksi di mana user ini adalah target, dan sumbernya adalah user lain.
                $connection = $this->connectionsAsTarget()
                    ->where('source_type', User::class)
                    ->first();

                // Jika koneksi ditemukan, kembalikan objek User dari sumbernya.
                // Jika tidak, kembalikan null.
                return $connection ? $connection->source : null;
            },
        );
    }
}
