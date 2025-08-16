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
        // 'parent_id', // Seharusnya sudah dihapus jika Anda menjalankan migrasi terakhir
    ];

    // Relasi ke user yang membuat record friend ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ===================================================================
    // CATATAN: Method di bawah ini adalah untuk arsitektur LAMA (parent_id)
    // ===================================================================

    /**
     * [BARU] Relasi untuk mendapatkan parent LANGSUNG dari friend ini.
     */
    public function parent()
    {
        return $this->belongsTo(Friend::class, 'parent_id');
    }

    /**
     * [BARU & MEMPERBAIKI ERROR] Relasi untuk mendapatkan anak-anak LANGSUNG.
     */
    public function children()
    {
        return $this->hasMany(Friend::class, 'parent_id');
    }

    /**
     * Relasi untuk mengambil semua turunan secara rekursif.
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Method untuk mengumpulkan semua ID turunan secara rekursif.
     */
    public function getAllChildrenIds()
    {
        $ids = [];
        foreach ($this->childrenRecursive as $child) {
            $ids[] = $child->id;
            if ($child->childrenRecursive->isNotEmpty()) {
                $ids = array_merge($ids, $child->getAllChildrenIds());
            }
        }
        return $ids;
    }

    // ===================================================================
    // METHOD UNTUK ARSITEKTUR BARU (connections)
    // ===================================================================
    
    /**
     * Relasi untuk semua koneksi di mana friend ini adalah SUMBER.
     */
    public function connections()
    {
        return $this->morphMany(Connection::class, 'source');
    }
}