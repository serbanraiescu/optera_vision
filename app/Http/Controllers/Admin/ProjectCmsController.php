<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Helpers\HtmlSanitizer;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCmsController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('locality', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $projects = $query->orderBy('sort_order', 'asc')
                          ->orderBy('id', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the creation form.
     */
    public function create()
    {
        return view('admin.projects.edit');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:projects,slug',
            'category' => 'required|string|max:100',
            'locality' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:250',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'required|string|max:255',
        ]);

        // 2. XSS Sanitization
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('full_description'));

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // 3. Create project
        $project = Project::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'slug_locked' => $request->filled('slug'),
            'category' => $request->input('category'),
            'locality' => $request->input('locality'),
            'short_description' => $request->input('short_description'),
            'full_description' => $sanitizedHtml,
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status', 'draft'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Sync dynamic image gallery rows
        if ($request->has('gallery_images')) {
            $galleryImages = $request->input('gallery_images');
            foreach ($galleryImages as $index => $path) {
                if (trim($path) !== '') {
                    $project->images()->create([
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        // 5. Audit Log
        $this->activityLogger->log(
            'project_created',
            "Proiectul '{$project->title}' (ID: {$project->id}) a fost creat de către {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.projects.index')->with('success', 'Lucrarea de portofoliu a fost adăugată cu succes.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Project $project)
    {
        // Eager load gallery images
        $project->load('images');
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:projects,slug,' . $project->id,
            'category' => 'required|string|max:100',
            'locality' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:250',
            'full_description' => 'nullable|string',
            'featured_image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'required|string|max:255',
        ]);

        // 2. XSS Sanitization
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('full_description'));

        // 3. Save updates
        $project->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('slug')),
            'slug_locked' => true,
            'category' => $request->input('category'),
            'locality' => $request->input('locality'),
            'short_description' => $request->input('short_description'),
            'full_description' => $sanitizedHtml,
            'featured_image' => $request->input('featured_image'),
            'status' => $request->input('status'),
            'is_featured' => $request->has('is_featured'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Sync dynamic image gallery rows
        if ($request->has('gallery_images')) {
            $galleryImages = $request->input('gallery_images');
            
            // Delete old ones not in the submitted list
            $project->images()->whereNotIn('image_path', $galleryImages)->delete();

            // Create new or reorder
            foreach ($galleryImages as $index => $path) {
                if (trim($path) !== '') {
                    $project->images()->updateOrCreate(
                        ['image_path' => $path],
                        ['sort_order' => $index]
                    );
                }
            }
        } else {
            $project->images()->delete();
        }

        // 5. Audit Log
        $this->activityLogger->log(
            'project_edited',
            "Proiectul '{$project->title}' (ID: {$project->id}) a fost editat de către {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.projects.index')->with('success', 'Lucrarea de portofoliu a fost actualizată cu succes.');
    }

    /**
     * Soft delete a project.
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();

        // 1. Audit Log
        $this->activityLogger->log(
            'project_deleted',
            "Proiectul '{$project->title}' (ID: {$project->id}) a fost șters permanent (soft deleted) de către {$user->email}.",
            $user->id
        );

        // 2. Soft delete
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Proiectul a fost mutat în arhivă cu succes.');
    }
}
