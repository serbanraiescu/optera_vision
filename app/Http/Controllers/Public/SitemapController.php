<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Project;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate dynamic, highly optimized XML Sitemap.
     * Implements strict permanent caching with manual rebuild overrides.
     */
    public function index()
    {
        $xml = Cache::remember('sitemap_xml', 86400, function () {
            $urls = [];

            // 1. Add Homepage
            $urls[] = [
                'loc' => url('/'),
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            // 2. Add published Services
            Service::published()->where('noindex', false)->chunk(50, function ($services) use (&$urls) {
                foreach ($services as $service) {
                    $urls[] = [
                        'loc' => route('services.show', $service->slug),
                        'lastmod' => $service->updated_at->format('Y-m-d'),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            });

            // 3. Add published Projects
            Project::published()->where('noindex', false)->chunk(50, function ($projects) use (&$urls) {
                foreach ($projects as $project) {
                    $urls[] = [
                        'loc' => route('projects.show', $project->slug),
                        'lastmod' => $project->updated_at->format('Y-m-d'),
                        'changefreq' => 'monthly',
                        'priority' => '0.6',
                    ];
                }
            });

            // 4. Add published Pages
            Page::published()->where('noindex', false)->chunk(50, function ($pages) use (&$urls) {
                foreach ($pages as $page) {
                    $urls[] = [
                        'loc' => route('pages.show', $page->slug),
                        'lastmod' => $page->updated_at->format('Y-m-d'),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                }
            });

            // Build dynamic XML
            $out = '<?xml version="1.0" encoding="UTF-8"?>';
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($urls as $url) {
                $out .= '<url>';
                $out .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
                $out .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
                $out .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
                $out .= '<priority>' . $url['priority'] . '</priority>';
                $out .= '</url>';
            }
            $out .= '</urlset>';

            return $out;
        });

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Generate dynamic, highly optimized robots.txt file.
     */
    public function robots()
    {
        $directives = setting('seo.robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml'));

        return response($directives, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
