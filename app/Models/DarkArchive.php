<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DarkArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_code',
        'title',
        'slug',
        'thumbnail',
        'content',
        'status',
        'incident_date',
        'location',
        'views',
        'respects',
        'user_id',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper untuk format tanggal ala dokumen rahasia
    public function getFormattedDateAttribute()
    {
        return $this->incident_date ? $this->incident_date->format('d M Y') : 'UNKNOWN';
    }

    public function getFormattedAddedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y - H:i') : 'UNKNOWN';
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d M Y - H:i') : 'UNKNOWN';
    }
}
