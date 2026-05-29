<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\ActivityLog;
use App\Models\Service;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the admin panel dashboard main screen with cached metrics.
     */
    public function index()
    {
        $user = auth()->user();
        $isTechnician = $user->role === 'technician';

        // 1. Determine cache key based on user role to respect permissions
        $cacheKey = $isTechnician ? 'admin_dashboard_metrics_tech_' . $user->id : 'admin_dashboard_metrics';

        $metrics = Cache::remember($cacheKey, 300, function () use ($isTechnician, $user) {
            $query = QuoteRequest::query();
            if ($isTechnician) {
                $query->where('assigned_to', $user->id);
            }

            $leads = $query->get();
            $activeLeads = $leads->filter(fn($l) => in_array($l->status->value, ['acceptat', 'in_lucru']));

            return [
                'total_quotes' => $leads->count(),
                'new_quotes' => $leads->filter(fn($l) => $l->status->value === 'nou')->count(),
                'contacted_quotes' => $leads->filter(fn($l) => $l->status->value === 'contactat')->count(),
                'accepted_quotes' => $leads->filter(fn($l) => $l->status->value === 'acceptat')->count(),
                'lost_quotes' => $leads->filter(fn($l) => $l->status->value === 'pierdut')->count(),
                'active_project_value' => (float) $activeLeads->sum('estimated_value'),
                'services_count' => Service::count(),
                'projects_count' => Project::count(),
            ];
        });

        // 2. Pull recent activity logs (Admins see all; technicians see none or logs related to assignments)
        $logQuery = ActivityLog::with('user')->orderBy('created_at', 'desc');
        if ($isTechnician) {
            $logQuery->where('user_id', $user->id);
        }
        $recentLogs = $logQuery->take(5)->get();

        // 3. Pull recent leads (Admins see all; technicians see assigned only)
        $leadQuery = QuoteRequest::orderBy('created_at', 'desc');
        if ($isTechnician) {
            $leadQuery->where('assigned_to', $user->id);
        }
        $recentQuotes = $leadQuery->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'recentLogs', 'recentQuotes'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notificarea a fost marcată ca citită.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toate notificările au fost marcate ca citite.');
    }
}
