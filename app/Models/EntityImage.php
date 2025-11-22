<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EntityImage extends Model
{
    protected $fillable = ['entity_id', 'path', 'caption'];

    // Agar JSON otomatis ada field 'url'
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if (Str::startsWith($this->path, ['http://', 'https://'])) {
            return $this->path;
        }

        $cleanPath = Str::replaceFirst('uploads/', '', $this->path);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('main_uploads');

        return $disk->url($cleanPath);
    }
}
