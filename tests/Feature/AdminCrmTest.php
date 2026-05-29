<?php

namespace Tests\Feature;

use App\Enums\QuoteStatus;
use App\Models\User;
use App\Models\QuoteRequest;
use App\Models\Client;
use App\Services\LeadValuationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrmTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;
    protected $operator;
    protected $technician;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create different test role users
        $this->superadmin = User::create([
            'name' => 'SuperAdmin Test',
            'email' => 'superadmin@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $this->operator = User::create([
            'name' => 'Operator Test',
            'email' => 'operator@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $this->technician = User::create([
            'name' => 'Technician Test',
            'email' => 'tech@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'technician',
        ]);
    }

    /**
     * Test LeadValuationService calculates pricing correctly.
     */
    public function test_lead_valuation_service_calculates_correct_pricing()
    {
        $service = resolve(LeadValuationService::class);

        $leadResidential = [
            'camera_count' => 4,
            'location_type' => 'rezidential'
        ];
        // 4 * 650 + 500 = 3100
        $this->assertEquals(3100, $service->calculate($leadResidential));

        $leadIndustrial = [
            'camera_count' => 10,
            'location_type' => 'industrial'
        ];
        // 10 * 650 + 2500 = 9000
        $this->assertEquals(9000, $service->calculate($leadIndustrial));
    }

    /**
     * Test superadmin has full CRM and Export access.
     */
    public function test_superadmin_can_access_crm_board_and_export()
    {
        QuoteRequest::create([
            'name' => 'Client A',
            'phone' => '0712345678',
            'email' => 'clienta@test.com',
            'location_type' => 'rezidential',
            'camera_count' => 4,
            'status' => QuoteStatus::NEW,
        ]);

        $response = $this->actingAs($this->superadmin)->get(route('admin.quotes'));
        $response->assertStatus(200);
        $response->assertSee('Client A');

        $exportResponse = $this->actingAs($this->superadmin)->get(route('admin.quotes.export'));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /**
     * Test technician can strictly view assigned leads only.
     */
    public function test_technician_is_restricted_to_assigned_leads_only()
    {
        // Lead 1: Assigned to our technician
        $assignedLead = QuoteRequest::create([
            'name' => 'Assigned Lead',
            'phone' => '0712345678',
            'email' => 'clienta@test.com',
            'location_type' => 'rezidential',
            'camera_count' => 4,
            'status' => QuoteStatus::NEW,
            'assigned_to' => $this->technician->id,
        ]);

        // Lead 2: Unassigned
        $unassignedLead = QuoteRequest::create([
            'name' => 'Unassigned Lead',
            'phone' => '0722345678',
            'email' => 'clientb@test.com',
            'location_type' => 'comercial',
            'camera_count' => 8,
            'status' => QuoteStatus::NEW,
        ]);

        // Access board as technician: see only Assigned
        $response = $this->actingAs($this->technician)->get(route('admin.quotes'));
        $response->assertStatus(200);
        $response->assertSee('Assigned Lead');
        $response->assertDontSee('Unassigned Lead');

        // Access assigned lead detail: 200
        $detailOk = $this->actingAs($this->technician)->get(route('admin.quotes.show', $assignedLead->id));
        $detailOk->assertStatus(200);

        // Access unassigned lead detail: 403 Forbidden
        $detailFail = $this->actingAs($this->technician)->get(route('admin.quotes.show', $unassignedLead->id));
        $detailFail->assertStatus(403);

        // Try to export CSV as technician: 403 Forbidden
        $exportFail = $this->actingAs($this->technician)->get(route('admin.quotes.export'));
        $exportFail->assertStatus(403);
    }

    /**
     * Test operator can view all leads and write internal notes.
     */
    public function test_operator_can_view_all_leads_and_write_timeline_notes()
    {
        $lead = QuoteRequest::create([
            'name' => 'Client Alpha',
            'phone' => '0712345678',
            'email' => 'clienta@test.com',
            'location_type' => 'rezidential',
            'camera_count' => 4,
            'status' => QuoteStatus::NEW,
        ]);

        $response = $this->actingAs($this->operator)->get(route('admin.quotes'));
        $response->assertStatus(200);
        $response->assertSee('Client Alpha');

        // Operator adds an internal CRM timeline note
        $noteResponse = $this->actingAs($this->operator)->post(route('admin.quotes.note', $lead->id), [
            'note' => 'Clientul a fost contactat telefonic. Stabilim evaluare.',
        ]);

        $noteResponse->assertRedirect();
        $this->assertDatabaseHas('quote_notes', [
            'quote_request_id' => $lead->id,
            'user_id' => $this->operator->id,
            'type' => 'note',
            'note' => 'Clientul a fost contactat telefonic. Stabilim evaluare.',
        ]);
    }

    /**
     * Test converting a lead to client registry with smart deduplication checks.
     */
    public function test_lead_converts_to_client_with_smart_deduplication()
    {
        $lead = QuoteRequest::create([
            'name' => 'Lead Vasile',
            'phone' => '0755555555',
            'email' => 'vasile@test.ro',
            'location_type' => 'rezidential',
            'camera_count' => 3,
            'status' => QuoteStatus::CONTACTED,
        ]);

        // 1. Initial conversion: creates new Client card
        $response = $this->actingAs($this->operator)->post(route('admin.clients.link', $lead->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'name' => 'Lead Vasile',
            'phone' => '0755555555',
            'email' => 'vasile@test.ro',
        ]);

        $client = Client::where('email', 'vasile@test.ro')->first();
        $this->assertEquals($client->id, $lead->fresh()->client_id);

        // 2. Second conversion (duplicate check): create another lead with same phone
        $secondLead = QuoteRequest::create([
            'name' => 'Vasile Reintors',
            'phone' => '0755555555', // Duplicate phone
            'email' => 'vasile.nou@test.ro',
            'location_type' => 'industrial',
            'camera_count' => 6,
            'status' => QuoteStatus::ACCEPTED,
        ]);

        $secondResponse = $this->actingAs($this->operator)->post(route('admin.clients.link', $secondLead->id));
        $secondResponse->assertRedirect();

        // Count of vasile in clients must remain exactly 1
        $this->assertEquals(1, Client::where('phone', '0755555555')->count());
        $this->assertEquals($client->id, $secondLead->fresh()->client_id);
    }
}
