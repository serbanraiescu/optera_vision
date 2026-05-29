<?php

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'title',
        'slug',
        'slug_locked',
        'short_description',
        'full_description',
        'icon_key',
        'featured_image',
        'status',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'noindex',
    ];

    protected $casts = [
        'slug_locked' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'noindex' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     * Invalidates public listing caches and sitemap caches automatically.
     */
    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('active_services_list');
            \Illuminate\Support\Facades\Cache::forget('sitemap_xml');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('active_services_list');
            \Illuminate\Support\Facades\Cache::forget('sitemap_xml');
        });
    }

    /**
     * Scope to retrieve only published services.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
