<?php

namespace App\Models;

use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'title',
        'slug',
        'slug_locked',
        'category',
        'locality',
        'short_description',
        'full_description',
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
     * Scope to retrieve only published projects.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Get the project gallery images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
