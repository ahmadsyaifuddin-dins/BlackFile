<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditView extends Model
{
    use HasFactory;
    public $timestamps = false; // Karena kita hanya butuh viewed_at
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'visitor_id', 'viewed_at'];

    /**
     * Relasi ke User yang memiliki halaman credit (pemilik).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User yang mengunjungi halaman (jika dia login).
     */
    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }
}
