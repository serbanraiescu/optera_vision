<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\Project;

class DashboardController extends Controller
{
    /**
     * Display the admin panel dashboard main screen.
     */
    public function index()
    {
        // For Phase 1, we pull mock metrics to ensure DB integration and template compilation work perfectly.
        $metrics = [
            'leads_count' => QuoteRequest::count(),
            'services_count' => Service::count(),
            'projects_count' => Project::count(),
            'recent_logs' => ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('admin.dashboard', compact('metrics'));
    }
}
