<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultMusic extends Model
{   
    protected $table = 'default_musics';
    use HasFactory;
    protected $fillable = ['name', 'path'];
}
