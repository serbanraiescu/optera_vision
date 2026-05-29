<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected const CACHE_KEY = 'site_settings';

    /**
     * Get a setting value by key, using query caching.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings as an associative key => value array, cached forever.
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Set/update a setting value and invalidate the cache.
     */
    public function set(string $key, mixed $value, string $group = 'general'): Setting
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        $this->clearCache();

        return $setting;
    }

    /**
     * Update multiple settings at once and invalidate cache.
     */
    public function setMany(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        $this->clearCache();
    }

    /**
     * Clear settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Export all settings as a JSON string.
     */
    public function exportJson(): string
    {
        $settings = Setting::all(['key', 'value', 'group']);
        return json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import settings from an array structure.
     */
    public function importArray(array $data): void
    {
        foreach ($data as $item) {
            if (isset($item['key'])) {
                Setting::updateOrCreate(
                    ['key' => $item['key']],
                    [
                        'value' => $item['value'] ?? null,
                        'group' => $item['group'] ?? 'general'
                    ]
                );
            }
        }
        $this->clearCache();
    }
}
