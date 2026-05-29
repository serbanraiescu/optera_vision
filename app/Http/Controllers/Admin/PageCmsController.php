<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Helpers\HtmlSanitizer;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageCmsController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a paginated listing of pages.
     */
    public function index(Request $request)
    {
        $query = Page::query()->with('parent');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pages = $query->orderBy('type', 'asc')->orderBy('title', 'asc')->paginate(10)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $masterTemplates = Page::where('type', 'local_seo')
            ->whereNull('parent_id')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.pages.edit', compact('masterTemplates'));
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:pages,slug',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'type' => 'required|in:legal,local_seo,custom',
            'parent_id' => 'nullable|integer|exists:pages,id',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
        ]);

        // 2. Server-side Rich HTML sanitization to prevent XSS
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('content'));

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // 3. Create record
        $page = Page::create([
            'parent_id' => $request->input('parent_id'),
            'title' => $request->input('title'),
            'slug' => $slug,
            'slug_locked' => $request->filled('slug'),
            'content' => $sanitizedHtml,
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Audit Log
        $this->activityLogger->log(
            'page_created',
            "Pagina '{$page->title}' (Tip: {$page->type}) a fost creată de {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.pages.index')->with('success', 'Pagina a fost creată cu succes.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Page $page)
    {
        $masterTemplates = Page::where('type', 'local_seo')
            ->whereNull('parent_id')
            ->where('id', '!=', $page->id)
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.pages.edit', compact('page', 'masterTemplates'));
    }

    /**
     * Update the page record.
     */
    public function update(Request $request, Page $page)
    {
        $user = auth()->user();

        // 1. Validation
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'type' => 'required|in:legal,local_seo,custom',
            'parent_id' => 'nullable|integer|exists:pages,id',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:250',
        ]);

        // 2. Server-side HTML Sanitization
        $sanitizedHtml = HtmlSanitizer::sanitize($request->input('content'));

        // 3. Save updates
        $page->update([
            'parent_id' => $request->input('parent_id'),
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('slug')),
            'slug_locked' => true,
            'content' => $sanitizedHtml,
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
        ]);

        // 4. Audit Log
        $this->activityLogger->log(
            'page_edited',
            "Pagina '{$page->title}' (ID: {$page->id}) a fost editată de {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.pages.index')->with('success', 'Pagina a fost actualizată cu succes.');
    }

    /**
     * Clone/Duplicate an existing page for fast scaling.
     */
    public function duplicate(Page $page)
    {
        $user = auth()->user();

        // Generate unique slug for the cloned page
        $originalSlug = $page->slug;
        $clonedSlug = $originalSlug . '-copie';
        
        $counter = 1;
        while (Page::where('slug', $clonedSlug)->exists()) {
            $clonedSlug = $originalSlug . '-copie-' . $counter;
            $counter++;
        }

        // Clone page record
        $clonedPage = Page::create([
            'parent_id' => $page->parent_id ?: $page->id, // If cloning a master, link clone to it!
            'title' => $page->title . ' (Copie)',
            'slug' => $clonedSlug,
            'slug_locked' => true,
            'content' => $page->content,
            'status' => 'draft', // Clones start as draft for safety
            'type' => $page->type,
            'meta_title' => $page->meta_title ? ($page->meta_title . ' - Copie') : null,
            'meta_description' => $page->meta_description,
        ]);

        // Audit Log
        $this->activityLogger->log(
            'page_duplicated',
            "Pagina '{$page->title}' a fost duplicată în '{$clonedPage->title}' (ID: {$clonedPage->id}) de către {$user->email}.",
            $user->id
        );

        return redirect()->route('admin.pages.edit', $clonedPage->id)
            ->with('success', 'Pagina a fost duplicată cu succes în ciornă (Draft).');
    }

    /**
     * Soft delete a page from storage.
     */
    public function destroy(Page $page)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Doar administratorii pot șterge pagini.');
        }

        // Audit Log
        $this->activityLogger->log(
            'page_deleted',
            "Pagina '{$page->title}' (ID: {$page->id}) a fost ștearsă (soft-deleted) de {$user->email}.",
            $user->id
        );

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Pagina a fost arhivată cu succes.');
    }
}
