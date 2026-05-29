<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuoteStatus;
use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\QuoteNote;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a listing of CRM quote requests with filters, search, and pagination.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isTechnician = $user->role === 'technician';

        // 1. Initialize query with scopes
        $query = QuoteRequest::query()->with('assignee');

        // Enforce technician constraints immediately
        if ($isTechnician) {
            $query->where('assigned_to', $user->id);
        }

        // Apply search scope
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply status filter scope
        if ($request->filled('status')) {
            $query->status($request->input('status'));
        }

        // Filter by Setup Type
        if ($request->filled('setup_type')) {
            $isUpgrade = $request->input('setup_type') === 'upgrade';
            $query->where('is_upgrade', $isUpgrade);
        }

        // Filter by Location Type
        if ($request->filled('location_type')) {
            $query->where('location_type', $request->input('location_type'));
        }

        // Filter by Assigned User (only visible to admins/operators)
        if (!$isTechnician && $request->filled('assigned_to')) {
            $query->assigned($request->input('assigned_to'));
        }

        // Apply Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 2. Fetch Paginated Records
        $quotes = $query->paginate(10)->withQueryString();

        // 3. Load lookup parameters for filter forms
        $statuses = QuoteStatus::cases();
        $assignees = !$isTechnician ? User::whereIn('role', ['admin', 'superadmin', 'operator', 'technician'])->get() : collect();

        return view('admin.quotes.index', compact('quotes', 'statuses', 'assignees'));
    }

    /**
     * Display the detailed workspace for a specific lead request.
     */
    public function show(QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Technician can only view their own assigned leads
        if ($user->role === 'technician' && $quoteRequest->assigned_to !== $user->id) {
            abort(403, 'Nu aveți permisiunea de a vizualiza această cerere de ofertă.');
        }

        // 2. Paginate/limit CRM timeline notes to prevent performance lag
        $timelineNotes = $quoteRequest->notes()->with('user')->paginate(8);

        // 3. Eager load list of assignable operators and technicians (Admins only)
        $staff = User::whereIn('role', ['superadmin', 'admin', 'operator', 'technician'])->get();
        $statuses = QuoteStatus::cases();

        return view('admin.quotes.show', compact('quoteRequest', 'timelineNotes', 'staff', 'statuses'));
    }

    /**
     * Update the status of a specific quote request.
     */
    public function updateStatus(Request $request, QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Technicians can only update status if lead is assigned to them
        if ($user->role === 'technician' && $quoteRequest->assigned_to !== $user->id) {
            abort(403, 'Nu aveți permisiunea de a actualiza stadiul acestei cereri.');
        }

        // 2. Validation
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_column(QuoteStatus::cases(), 'value')),
        ]);

        $oldStatus = $quoteRequest->status;
        $newStatus = QuoteStatus::from($request->input('status'));

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Stadiul cererii este deja cel selectat.');
        }

        // 3. Save status transition
        $quoteRequest->update([
            'status' => $newStatus
        ]);

        // 4. Log in chronological timeline
        QuoteNote::create([
            'quote_request_id' => $quoteRequest->id,
            'user_id' => $user->id,
            'type' => 'status_change',
            'note' => "Stadiu actualizat din '{$oldStatus->label()}' în '{$newStatus->label()}' de către {$user->name}.",
        ]);

        // 5. Fire security audit
        $this->activityLogger->log(
            'quote_status_updated',
            "Stadiul solicitării #{$quoteRequest->id} ({$quoteRequest->name}) a fost schimbat în '{$newStatus->value}' de {$user->email}.",
            $user->id
        );

        // Fire database notifications if marked important
        if ($quoteRequest->is_important) {
            $admins = User::whereIn('role', ['superadmin', 'admin'])->get();
            $notificationData = [
                'title' => "Modificare stadiu solicitare importantă #{$quoteRequest->id}",
                'message' => "Lead-ul important {$quoteRequest->name} a fost trecut în stadiul {$newStatus->label()} de {$user->name}.",
                'lead_id' => $quoteRequest->id
            ];
            foreach ($admins as $admin) {
                if ($admin->id !== $user->id) {
                    $admin->notifications()->create([
                        'id' => \Illuminate\Support\Str::uuid(),
                        'type' => 'App\Notifications\ImportantQuoteStatusChanged',
                        'data' => json_encode($notificationData),
                    ]);
                }
            }
        }

        return back()->with('success', "Stadiul cererii de ofertă a fost modificat în '{$newStatus->label()}'.");
    }

    /**
     * Add a custom internal CRM note to the lead timeline.
     */
    public function addNote(Request $request, QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Technicians can only add notes to their own assigned leads
        if ($user->role === 'technician' && $quoteRequest->assigned_to !== $user->id) {
            abort(403, 'Nu aveți permisiunea de a adăuga notițe pentru această cerere.');
        }

        // 2. Validation
        $request->validate([
            'note' => 'required|string|min:3|max:1000',
        ]);

        // 3. Write note record
        QuoteNote::create([
            'quote_request_id' => $quoteRequest->id,
            'user_id' => $user->id,
            'type' => 'note',
            'note' => trim($request->input('note')),
        ]);

        // 4. Audit logging
        $this->activityLogger->log(
            'quote_note_added',
            "Notiță adăugată la solicitarea #{$quoteRequest->id} de {$user->email}.",
            $user->id
        );

        return back()->with('success', 'Notița internă a fost salvată pe timeline.');
    }

    /**
     * Reassign a quote request to a different operator or technician.
     */
    public function assignUser(Request $request, QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Restricted to Admin and SuperAdmin
        if (!$user->isAdmin()) {
            abort(403, 'Doar administratorii pot reatribui cereri de ofertă.');
        }

        // 2. Validation
        $request->validate([
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $assigneeId = $request->input('assigned_to');
        $oldAssignee = $quoteRequest->assignee;

        if ($assigneeId) {
            $newAssignee = User::findOrFail($assigneeId);
            $quoteRequest->update([
                'assigned_to' => $newAssignee->id,
                'assigned_at' => now(),
            ]);

            // Write timeline
            QuoteNote::create([
                'quote_request_id' => $quoteRequest->id,
                'user_id' => $user->id,
                'type' => 'assignment_change',
                'note' => "Cerere repartizată către {$newAssignee->name} (Rol: {$newAssignee->role}) de către {$user->name}.",
            ]);

            // Notify assigned user
            if ($newAssignee->id !== $user->id) {
                $newAssignee->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'App\Notifications\LeadAssigned',
                    'data' => json_encode([
                        'title' => "Ai fost desemnat responsabil pentru lead-ul #{$quoteRequest->id}",
                        'message' => "Administratorul {$user->name} te-a desemnat responsabil pentru {$quoteRequest->name}.",
                        'lead_id' => $quoteRequest->id
                    ]),
                ]);
            }

            $msg = "Lead-ul a fost repartizat cu succes către {$newAssignee->name}.";
        } else {
            $quoteRequest->update([
                'assigned_to' => null,
                'assigned_at' => null,
            ]);

            QuoteNote::create([
                'quote_request_id' => $quoteRequest->id,
                'user_id' => $user->id,
                'type' => 'assignment_change',
                'note' => "Desemnarea responsabilității a fost retrasă de către {$user->name}.",
            ]);

            $msg = "Responsabilitatea a fost retrasă.";
        }

        $this->activityLogger->log(
            'quote_reassigned',
            "Solicitarea #{$quoteRequest->id} a fost reatribuită de {$user->email}.",
            $user->id
        );

        return back()->with('success', $msg);
    }

    /**
     * Toggle the "important" starred priority flag on a lead.
     */
    public function toggleImportant(QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Technicians cannot change priority priority stars
        if ($user->role === 'technician') {
            abort(403, 'Nu aveți permisiunea de a marca priorități.');
        }

        $newValue = !$quoteRequest->is_important;
        $quoteRequest->update([
            'is_important' => $newValue
        ]);

        // 2. Log in timeline
        QuoteNote::create([
            'quote_request_id' => $quoteRequest->id,
            'user_id' => $user->id,
            'type' => 'priority_change',
            'note' => $newValue ? "Lead marcat ca IMPORTANT de către {$user->name}." : "Prioritatea IMPORTANTĂ a fost retrasă de către {$user->name}.",
        ]);

        return back()->with('success', $newValue ? 'Cererea a fost marcată ca importantă!' : 'Prioritatea a fost retrasă.');
    }

    /**
     * Soft delete a lead request and record audit log.
     */
    public function destroy(QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: Restricted to Admin/SuperAdmin
        if (!$user->isAdmin()) {
            abort(403, 'Doar administratorii pot șterge cereri de ofertă.');
        }

        // 2. Audit logger
        $this->activityLogger->log(
            'quote_deleted',
            "Cererea de ofertă #{$quoteRequest->id} ({$quoteRequest->name}) a fost ștearsă (soft-deleted) de {$user->email}.",
            $user->id
        );

        // 3. Delete
        $quoteRequest->delete();

        return redirect()->route('admin.quotes')->with('success', 'Cererea de ofertă a fost arhivată cu succes.');
    }
}
