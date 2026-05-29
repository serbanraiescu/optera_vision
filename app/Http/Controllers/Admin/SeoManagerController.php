<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Project;
use App\Models\Page;
use App\Services\ActivityLogService;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SeoManagerController extends Controller
{
    protected $settingsService;
    protected $activityLogger;

    public function __construct(SettingsService $settingsService, ActivityLogService $activityLogger)
    {
        $this->settingsService = $settingsService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display the SEO manager cockpit dashboard.
     */
    public function index()
    {
        // 1. Fetch global SEO settings
        $globalSeo = [
            'seo.default_title' => setting('seo.default_title', 'Sisteme Supraveghere Video Câmpulung Moldovenesc'),
            'seo.default_description' => setting('seo.default_description', 'Sisteme de supraveghere video profesionale în Câmpulung Moldovenesc și Bucovina.'),
            'seo.default_og_image' => setting('seo.default_og_image'),
            'system.robots_directives' => setting('system.robots_directives', "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml')),
        ];

        // 2. Fetch list of entities to manage their SEO metadata
        $services = Service::orderBy('title', 'asc')->get();
        $projects = Project::orderBy('title', 'asc')->get();
        $pages = Page::orderBy('type', 'asc')->orderBy('title', 'asc')->get();

        return view('admin.seo.index', compact('globalSeo', 'services', 'projects', 'pages'));
    }

    /**
     * Process per-entity SEO overrides.
     */
    public function updateEntitySeo(Request $request, string $type, int $id)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
            'noindex' => 'nullable',
        ]);

        $noindex = $request->has('noindex') ? (bool) $request->input('noindex') : false;

        // 2. Retrieve entity and save overrides
        $entity = null;
        $title = '';

        switch ($type) {
            case 'service':
                $entity = Service::findOrFail($id);
                break;
            case 'project':
                $entity = Project::findOrFail($id);
                break;
            case 'page':
                $entity = Page::findOrFail($id);
                break;
            default:
                abort(400, 'Tipul entității este invalid.');
        }

        $entity->update([
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'noindex' => $noindex,
        ]);

        $title = $entity->title;

        // 3. Record Audit log
        $this->activityLogger->log(
            'seo_override_updated',
            "Setările SEO ale entității {$type} '{$title}' (ID: {$id}) au fost actualizate de {$user->email}.",
            $user->id
        );

        return back()->with('success', "Meta tagurile SEO pentru '{$title}' au fost salvate cu succes.");
    }
}
