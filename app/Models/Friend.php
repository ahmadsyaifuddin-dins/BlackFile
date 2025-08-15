<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'codename',
        'parent_id',
    ];

    // Relasi ke user pemilik
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke parent (teman di level atas)
    public function parent()
    {
        return $this->belongsTo(Friend::class, 'parent_id');
    }

    // Relasi ke anak (teman di level bawah)
    public function children()
    {
        return $this->hasMany(Friend::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
