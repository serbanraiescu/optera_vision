<?php

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'title',
        'slug',
        'slug_locked',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'type',
    ];

    protected $casts = [
        'slug_locked' => 'boolean',
    ];

    /**
     * Scope to retrieve only published pages.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to retrieve only published legal pages.
     */
    public function scopeLegal(Builder $query): Builder
    {
        return $query->where('type', 'legal');
    }

    /**
     * Scope to retrieve only published local SEO landing pages.
     */
    public function scopeLocalSeo(Builder $query): Builder
    {
        return $query->where('type', 'local_seo');
    }

    /**
     * Get the layout sections belonging to this page.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }
}
