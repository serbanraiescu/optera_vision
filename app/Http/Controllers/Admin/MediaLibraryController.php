<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    protected $mediaService;
    protected $activityLogger;

    public function __construct(MediaService $mediaService, ActivityLogService $activityLogger)
    {
        $this->mediaService = $mediaService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a listing of files or folders.
     */
    public function index(Request $request)
    {
        $query = Media::query();

        // 1. Filter by folder segment
        $activeFolder = $request->input('folder', 'general');
        $query->where('folder', $activeFolder);

        // 2. Filter by search query
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('original_name', 'like', "%{$search}%");
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $folders = ['logos', 'services', 'projects', 'pages', 'seo', 'general'];

        // If AJAX request (modal selector), return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'files' => $files->items(),
                'pagination' => $files->linkCollection()->toArray()
            ]);
        }

        return view('admin.media.index', compact('files', 'folders', 'activeFolder'));
    }

    /**
     * Handle asynchronous image uploads.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // 1. Security: Strict validation filters. SVGs are strictly blocked. Size limit: 5MB.
        $request->validate([
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120', // 5MB
            ],
            'folder' => 'required|in:logos,services,projects,pages,seo,general',
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder');

        // 2. Extra Security: Verify image dimensions (max 4000x4000px)
        $dimensions = @getimagesize($file->getRealPath());
        if ($dimensions) {
            $width = $dimensions[0];
            $height = $dimensions[1];
            if ($width > 4000 || $height > 4000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rezoluția imaginii este prea mare. Limita maximă este de 4000x4000px.'
                ], 422);
            }
        }

        // 3. Process upload using MediaService
        $media = $this->mediaService->upload($file, $folder);

        // 4. Audit logging
        $this->activityLogger->log(
            'media_uploaded',
            "Fișierul '{$media->original_name}' (ID: {$media->id}) a fost încărcat în folderul '{$folder}'.",
            $user->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Fișierul a fost încărcat și procesat cu succes.',
            'media' => $media
        ]);
    }

    /**
     * Delete a media asset from storage.
     */
    public function destroy(Media $media)
    {
        $user = auth()->user();

        // 1. Audit logger
        $this->activityLogger->log(
            'media_deleted',
            "Fișierul '{$media->original_name}' (ID: {$media->id}) a fost șters permanent.",
            $user->id
        );

        // 2. Delete (The Media Model booted deleted listener automatically clears physical original, WebP and thumbnail files!)
        $media->delete();

        return redirect()->back()->with('success', 'Fișierul a fost șters permanent de pe disc.');
    }
}
