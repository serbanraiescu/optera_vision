<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageController
{
    /**
     * Display the dynamic legal page or dynamic local SEO page.
     */
    public function show(string $slug)
    {
        // 1. Prime anti-bot speed timestamp inside session in case forms exist on pages
        if (!session()->has('quote_form_loaded_at')) {
            session()->put('quote_form_loaded_at', time());
        }

        // 2. Fetch page dynamically based on status and cache it to increase performance
        $page = Cache::remember("dynamic_page_{$slug}", 3600, function () use ($slug) {
            return Page::published()->where('slug', $slug)->first();
        });

        if (!$page) {
            abort(404, 'Pagina solicitată nu există sau a fost arhivată.');
        }

        // 3. Render dynamic SEO landing pages or standard legal documents
        if ($page->type === 'local_seo') {
            // Retrieve dynamic locality parameters from the settings repository
            $locality = setting('company.locality', 'Câmpulung Moldovenesc');
            $county = setting('company.county', 'Suceava');

            // Clone to avoid mutating the cached page model directly
            $page = clone $page;

            // Restrict replacements strictly to local SEO types
            $page->content = str_replace(
                ['[localitate]', '[judet]', '[localitate_articulat]'],
                [$locality, $county, $locality . 'ul'],
                $page->content
            );

            $page->title = str_replace(
                ['[localitate]', '[judet]', '[localitate_articulat]'],
                [$locality, $county, $locality . 'ul'],
                $page->title
            );

            return view('public.page_seo', compact('page'));
        }

        return view('public.page', compact('page'));
    }
}
