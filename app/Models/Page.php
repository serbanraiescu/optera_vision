<?php

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'slug_locked',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'noindex',
        'type',
    ];

    protected $casts = [
        'slug_locked' => 'boolean',
        'noindex' => 'boolean',
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
     * Get the master parent template page, if any.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    /**
     * Get all child duplicate pages branched from this page.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Get the layout sections belonging to this page.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }
}
