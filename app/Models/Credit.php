<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role',
        'names',
        'logos',
        'music_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'logos' => 'array', // Automatically cast the JSON column to an array
        'names' => 'array', // Automatically cast the JSON column to an array
    ];

    /**
     * Get the user that owns the credit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}