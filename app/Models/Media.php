<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'thumbnail_path',
        'mime_type',
        'size',
        'width',
        'height',
        'folder',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    /**
     * The "booted" method of the model.
     * Orphan-proof file cleanup triggers upon record deletion.
     */
    protected static function booted()
    {
        static::deleted(function ($media) {
            // Delete main file
            if ($media->path && Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }

            // Delete thumbnail file
            if ($media->thumbnail_path && Storage::disk('public')->exists($media->thumbnail_path)) {
                Storage::disk('public')->delete($media->thumbnail_path);
            }
        });
    }

    /**
     * Helper to get the absolute public asset URL for the main file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Helper to get the absolute public asset URL for the thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : $this->url;
    }
}
