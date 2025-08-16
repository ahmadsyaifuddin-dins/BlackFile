<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function source() { return $this->morphTo(); }
    public function target() { return $this->morphTo(); }

}
