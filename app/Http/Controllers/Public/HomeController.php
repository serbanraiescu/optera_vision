<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the dynamic homepage with section blocks.
     */
    public function index()
    {
        // 1. Priming the anti-bot speed timestamp inside the user's session
        session()->put('quote_form_loaded_at', time());

        // 2. Fetching the homepage eager-loaded with its sections and nested content blocks (0 N+1 queries!)
        $page = Cache::remember('homepage_structure', 3600, function () {
            return Page::where('slug', 'home')
                ->where('status', 'published')
                ->with(['sections' => function ($query) {
                    $query->orderBy('sort_order');
                }, 'sections.blocks' => function ($query) {
                    $query->orderBy('sort_order');
                }])
                ->first();
        });

        // 3. Fallback check if seeder hasn't run or page is missing
        if (!$page) {
            abort(404, 'Homepage-ul nu a fost configurat în baza de date.');
        }

        return view('public.home', compact('page'));
    }
}
