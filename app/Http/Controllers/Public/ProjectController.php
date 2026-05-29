<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the active projects, paginated.
     */
    public function index()
    {
        // Paginate by 6 items to prevent performance issues and ensure clean page breaks
        $projects = Project::published()
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(6);

        return view('public.projects.index', compact('projects'));
    }

    /**
     * Display the specified active project.
     */
    public function show(string $slug)
    {
        // Eager load the project images to completely avoid N+1 query loop errors
        $project = Project::published()
            ->where('slug', $slug)
            ->with('images')
            ->firstOrFail();

        // Query related projects (same category or same locality, excluding this one)
        $relatedProjects = Project::published()
            ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                $query->where('category', $project->category)
                      ->orWhere('locality', $project->locality);
            })
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        // Fallback to latest projects if not enough related ones were found
        if ($relatedProjects->count() < 3) {
            $excludeIds = $relatedProjects->pluck('id')->push($project->id)->toArray();
            $latest = Project::published()
                ->whereNotIn('id', $excludeIds)
                ->orderBy('sort_order')
                ->take(3 - $relatedProjects->count())
                ->get();
                
            $relatedProjects = $relatedProjects->merge($latest);
        }

        return view('public.projects.show', compact('project', 'relatedProjects'));
    }
}
