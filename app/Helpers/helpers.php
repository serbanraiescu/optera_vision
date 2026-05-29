<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Helper to retrieve site settings using cached SettingsService lookups.
     * If no key is provided, returns the SettingsService instance itself.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $service = app(SettingsService::class);

        if (is_null($key)) {
            return $service;
        }

        return $service->get($key, $default);
    }
}
