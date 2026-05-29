<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Helpers\HtmlSanitizer;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCmsController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $services = $query->orderBy('sort_order', 'asc')
                          ->orderBy('title', 'asc')
                          ->paginate(10)
                          ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the creation form.
     */
    public function create()
    {
        return view('admin.services.edit');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:services,slug',
            'short_description' => 'nullable|string|max:250',
            'full_description' => 'nullable|string',
            'icon_key' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
        ]);

        // 2. Server-side XSS Sanitization for rich text HTML
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('full_description'));

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // 3. Create service
        $service = Service::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'slug_locked' => $request->filled('slug'),
            'short_description' => $request->input('short_description'),
            'full_description' => $sanitizedHtml,
            'icon_key' => $request->input('icon_key', 'shield'),
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status', 'draft'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Audit Log
        $this->activityLogger->log(
            'service_created',
            "Serviciul '{$service->title}' (ID: {$service->id}) a fost creat de către {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.services.index')->with('success', 'Serviciul a fost adăugat cu succes.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string|max:250',
            'full_description' => 'nullable|string',
            'icon_key' => 'nullable|string|max:50',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
        ]);

        // 2. Server-side XSS Sanitization
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('full_description'));

        // 3. Save updates
        $service->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('slug')),
            'slug_locked' => true,
            'short_description' => $request->input('short_description'),
            'full_description' => $sanitizedHtml,
            'icon_key' => $request->input('icon_key', 'shield'),
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Audit Log
        $this->activityLogger->log(
            'service_edited',
            "Serviciul '{$service->title}' (ID: {$service->id}) a fost editat de către {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.services.index')->with('success', 'Serviciul a fost actualizat cu succes.');
    }

    /**
     * Soft delete a service.
     */
    public function destroy(Service $service)
    {
        $user = auth()->user();

        // 1. Audit Log
        $this->activityLogger->log(
            'service_deleted',
            "Serviciul '{$service->title}' (ID: {$service->id}) a fost șters permanent (soft deleted) de către {$user->email}.",
            $user->id
        );

        // 2. Soft delete
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Serviciul a fost mutat în arhivă cu succes.');
    }
}
