<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\QuoteRequest;
use App\Models\QuoteNote;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display the registry list of persistent clients.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'technician') {
            abort(403, 'Nu aveți permisiunea de a accesa registrul de clienți.');
        }

        $query = Client::query();

        // Optional search parameters
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('locality', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Convert an accepted/contacted quote request into a persistent client with duplication check.
     */
    public function linkFromLead(QuoteRequest $quoteRequest)
    {
        $user = auth()->user();

        // 1. Authorization: technicians are strictly blocked from client registers
        if ($user->role === 'technician') {
            abort(403, 'Nu aveți permisiunea de a crea sau asocia clienți.');
        }

        if ($quoteRequest->client_id) {
            return back()->with('info', 'Această cerere este deja asociată unui client din registru.');
        }

        // 2. Smart Deduplication Check: Check by email OR phone
        $existingClient = Client::where(function ($q) use ($quoteRequest) {
            $q->where('email', $quoteRequest->email)
              ->orWhere('phone', $quoteRequest->phone);
        })->first();

        if ($existingClient) {
            // Found duplicate: link directly
            $quoteRequest->update([
                'client_id' => $existingClient->id
            ]);

            // Add note to timeline
            QuoteNote::create([
                'quote_request_id' => $quoteRequest->id,
                'user_id' => $user->id,
                'type' => 'client_link',
                'note' => "Cerere asociată automat cu clientul existent din registru: {$existingClient->name} ({$existingClient->email}).",
            ]);

            // Log activity
            $this->activityLogger->log(
                'lead_linked_client_existing',
                "Cererea de ofertă #{$quoteRequest->id} a fost asociată cu clientul existent #{$existingClient->id} ({$existingClient->name}).",
                $user->id
            );

            return back()->with('success', "Cererea a fost asociată cu succes cu clientul existent '{$existingClient->name}'.");
        }

        // 3. No duplicate found: Create new persistent Client card
        $newClient = Client::create([
            'name' => $quoteRequest->name,
            'phone' => $quoteRequest->phone,
            'email' => $quoteRequest->email,
            'locality' => $quoteRequest->locality,
            'source' => $quoteRequest->lead_source ?: 'configurator_online',
            'notes' => "Client înregistrat automat în urma cererii de ofertă #{$quoteRequest->id}.",
        ]);

        // Link lead to new client
        $quoteRequest->update([
            'client_id' => $newClient->id
        ]);

        // Add note to timeline
        QuoteNote::create([
            'quote_request_id' => $quoteRequest->id,
            'user_id' => $user->id,
            'type' => 'client_link',
            'note' => "Client nou creat și asociat în registru: {$newClient->name}.",
        ]);

        // Log activity
        $this->activityLogger->log(
            'lead_linked_client_new',
            "Cererea de ofertă #{$quoteRequest->id} a fost convertită în client nou #{$newClient->id} ({$newClient->name}).",
            $user->id
        );

        return back()->with('success', "Clientul '{$newClient->name}' a fost înregistrat și asociat cu succes.");
    }
}
