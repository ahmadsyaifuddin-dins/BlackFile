<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditView extends Model
{
    use HasFactory;
    public $timestamps = false; // Karena kita hanya butuh viewed_at
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'visitor_id', 'viewed_at'];
}