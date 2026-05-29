<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUniqueSlug
{
    /**
     * Boot the trait and register model event listeners.
     */
    protected static function bootHasUniqueSlug(): void
    {
        static::saving(function ($model) {
            // If the slug is manually changed and is not empty, lock it so it doesn't get overridden by title changes.
            if ($model->isDirty('slug') && !empty($model->slug)) {
                $model->slug = Str::slug($model->slug);
                $model->slug_locked = true;
            }

            // Generate slug automatically from title if it's not locked AND (slug is empty OR the title has changed)
            if (!$model->slug_locked && (empty($model->slug) || $model->isDirty('title'))) {
                $model->slug = static::generateUniqueSlug($model->title, $model->id);
            }
        });
    }

    /**
     * Generate a unique slug for the model.
     */
    public static function generateUniqueSlug(string $title, int|string|null $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Perform lookups until a unique slug is guaranteed in this table
        while (static::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
