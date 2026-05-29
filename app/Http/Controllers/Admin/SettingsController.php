<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    protected $settingsService;
    protected $activityLogger;

    public function __construct(SettingsService $settingsService, ActivityLogService $activityLogger)
    {
        $this->settingsService = $settingsService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display the settings workspace tabs.
     */
    public function index()
    {
        // Load settings values directly
        $branding = [
            'site.name' => setting('site.name', 'Optera Vision'),
            'brand.logo' => setting('brand.logo'),
            'brand.favicon' => setting('brand.favicon'),
            'brand.footer_logo' => setting('brand.footer_logo'),
            'brand.primary_color' => setting('brand.primary_color', '#0F3D24'),
            'brand.secondary_color' => setting('brand.secondary_color', '#164E2D'),
            'brand.accent_color' => setting('brand.accent_color', '#4ADE80'),
        ];

        $company = [
            'company.name' => setting('company.name', 'Optera Vision'),
            'company.cui' => setting('company.cui', 'RO00000000'),
            'company.reg_number' => setting('company.reg_number', 'J33/000/2026'),
            'company.address' => setting('company.address', 'Calea Transilvaniei'),
            'company.locality' => setting('company.locality', 'Câmpulung Moldovenesc'),
            'company.county' => setting('company.county', 'Suceava'),
            'company.phone' => setting('company.phone', '+40700000000'),
            'company.whatsapp' => setting('company.whatsapp', '+40700000000'),
            'company.email' => setting('company.email', 'office@opteravision.ro'),
            'company.hours' => setting('company.hours', 'Luni-Vineri: 09:00-18:00'),
            'company.areas' => setting('company.areas', 'Bucovina, Câmpulung Moldovenesc, Vatra Dornei, Gura Humorului, Suceava'),
        ];

        $contact = [
            'contact.google_maps' => setting('contact.google_maps'),
            'contact.facebook' => setting('contact.facebook'),
            'contact.instagram' => setting('contact.instagram'),
            'contact.whatsapp_link' => setting('contact.whatsapp_link'),
            'contact.emergency_phone' => setting('contact.emergency_phone'),
        ];

        $legal = [
            'legal.anpc' => setting('legal.anpc', 'https://anpc.ro/'),
            'legal.sol' => setting('legal.sol', 'https://ec.europa.eu/consumers/odr/'),
            'legal.cookies_text' => setting('legal.cookies_text', 'Acest site folosește cookie-uri pentru a îmbunătăți experiența de utilizare.'),
        ];

        $system = [
            'seo.default_title' => setting('seo.default_title'),
            'seo.default_description' => setting('seo.default_description'),
            'schema.organization_enabled' => setting('schema.organization_enabled', true),
            'schema.local_business_enabled' => setting('schema.local_business_enabled', true),
            'schema.service_enabled' => setting('schema.service_enabled', true),
        ];

        return view('admin.settings.index', compact('branding', 'company', 'contact', 'legal', 'system'));
    }

    /**
     * Process updates to settings dictionary.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $group = $request->input('group', 'general');

        // Security role checks: Only admins/superadmins can update Branding, System, or Cache configs
        if (in_array($group, ['branding', 'system']) && !$user->isAdmin()) {
            abort(403, 'Nu aveți permisiunea de a modifica setările de sistem sau de branding.');
        }

        $allInputs = $request->except(['_token', 'group']);

        // Map underscore keys back to dot-notation keys (as raw PHP POST parser converts dot to underscore)
        $mappedInputs = [];
        $existingKeys = \App\Models\Setting::where('group', $group)->pluck('key')->toArray();

        foreach ($allInputs as $rawKey => $value) {
            $matched = false;
            foreach ($existingKeys as $dbKey) {
                if (str_replace('.', '_', $dbKey) === $rawKey) {
                    $mappedInputs[$dbKey] = $value;
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                // Keep raw key if it did not match any dot-notation keys
                $mappedInputs[$rawKey] = $value;
            }
        }

        // Update settings dictionary through SettingsService
        $this->settingsService->setMany($mappedInputs, $group);

        // Record activity log for audit
        $this->activityLogger->log(
            'settings_changed',
            "Setările din grupul '{$group}' au fost actualizate de {$user->email}.",
            $user->id
        );

        return back()->with('success', "Setările din grupul '{$group}' au fost salvate cu succes și cache-ul a fost curățat.");
    }

    /**
     * Predefined, controlled administrative actions for cache clearing.
     */
    public function clearSystemCache()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort(403, 'Doar administratorii pot goli cache-ul sistemului.');
        }

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Also clear settings cache explicitly
        $this->settingsService->clearCache();

        $this->activityLogger->log(
            'system_cache_cleared',
            "Cache-ul complet al sistemului a fost golit de către {$user->email}.",
            $user->id
        );

        return back()->with('success', 'Toate cache-urile sistemului (configurări, rute, vederi, setări) au fost golite cu succes.');
    }

    /**
     * Secure admin action to flush and rebuild sitemap.
     */
    public function rebuildSitemap()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort(403, 'Doar administratorii pot reconstrui sitemap-ul.');
        }

        // Flush dynamic sitemap cache
        Cache::forget('sitemap_xml');

        $this->activityLogger->log(
            'sitemap_rebuilt',
            "Sitemap-ul XML a fost marcat pentru reconstrucție de către {$user->email}.",
            $user->id
        );

        return back()->with('success', 'Sitemap-ul XML a fost golit din cache și va fi reconstruit automat la următorul crawl.');
    }
}
