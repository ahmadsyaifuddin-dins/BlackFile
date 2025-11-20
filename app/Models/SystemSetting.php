<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    // Helper untuk mengecek status dengan mudah (Static Access)
    public static function check(string $key, bool $default = true): bool
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        return $setting->value === '1'; // Simpan sebagai string '1' untuk true
    }
}
