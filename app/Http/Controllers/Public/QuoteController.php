<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\QuoteSubmitRequest;
use App\Models\QuoteRequest;
use App\Models\QuoteNote;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class QuoteController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Show the quote request configurator form.
     */
    public function index()
    {
        // Prime the anti-bot timestamp in session
        session()->put('quote_form_loaded_at', time());

        return view('public.quote');
    }

    /**
     * Process public configurator submissions with robust anti-spam and CRM automation.
     */
    public function submit(QuoteSubmitRequest $request)
    {
        $ip = $request->ip();
        $rateLimitKey = 'submit_quote|' . $ip;

        // 1. Rate limiting: restrict to 3 requests per IP per hour to prevent flood spamming
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);

            $this->activityLogger->log(
                'spam_blocked',
                "Limită de trimiteri depășită pentru IP {$ip}."
            );

            return response()->json([
                'success' => false,
                'message' => "Ați depășit limita de trimiteri. Vă rugăm să încercați din nou în {$minutes} minute."
            ], 429);
        }

        // Hit the rate limit counter
        RateLimiter::hit($rateLimitKey, 3600); // 1 hour block (3600 seconds)

        try {
            // 2. Pricing estimation algorithm from the imported UI (for CRM valuation metrics)
            $cameraCount = intval($request->input('camere'));
            $locationType = $request->input('tip_locatie');
            
            $estimatedValue = $cameraCount * 650;
            switch ($locationType) {
                case 'industrial':
                    $estimatedValue += 2500;
                    break;
                case 'comercial':
                    $estimatedValue += 1500;
                    break;
                case 'public':
                    $estimatedValue += 1000;
                    break;
                default: // rezidential
                    $estimatedValue += 500;
                    break;
            }

            // 3. Save Lead in database
            $lead = QuoteRequest::create([
                'name' => $request->input('nume'),
                'phone' => $request->input('telefon'),
                'email' => $request->input('email'),
                'locality' => $request->input('localitate'),
                'location_type' => $locationType,
                'is_upgrade' => $request->input('tip_serviciu') === 'upgrade',
                'camera_count' => $cameraCount,
                'message' => $request->input('mesaj'),
                'lead_source' => 'configurator_online',
                'status' => 'nou',
            ]);

            // 4. Record CRM Unified Timeline Activity Log
            QuoteNote::create([
                'quote_request_id' => $lead->id,
                'user_id' => null, // system automated log
                'type' => 'created',
                'note' => "Cerere de ofertă înregistrată online cu succes. Valoare estimată proiect: {$estimatedValue} RON.",
                'metadata' => [
                    'ip' => $ip,
                    'estimated_value' => $estimatedValue,
                ]
            ]);

            // Log administrative activity
            $this->activityLogger->log(
                'lead_received',
                "Cerere nouă de ofertă primită de la {$lead->name} ({$lead->email}) pentru {$lead->camera_count} camere."
            );

            // 5. Send dynamic SMTP notification email to admin
            $this->sendAdminMailNotification($lead, $estimatedValue);

            return response()->json([
                'success' => true,
                'message' => 'Cererea dvs. a fost trimisă cu succes!'
            ]);

        } catch (\Throwable $e) {
            // Log unexpected failure
            $this->activityLogger->log(
                'lead_error',
                "Eroare la procesarea cererii de ofertă: " . $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'A apărut o eroare la salvarea solicitării dvs. Vă rugăm să încercați din nou.'
            ], 500);
        }
    }

    /**
     * Dispatch SMTP alert mail or fire database notifications on connection failures.
     */
    protected function sendAdminMailNotification(QuoteRequest $lead, float $estimatedValue): void
    {
        // Fallback to .env from address if database setting is the default or invalid
        $adminEmail = setting('smtp.admin_email');
        if (!$adminEmail || $adminEmail === 'admin@optervision.ro') {
            $adminEmail = config('mail.from.address') ?: 'office@opteravision.ro';
        }

        try {
            // Only dynamically override SMTP configurations if custom credentials exist in the settings table
            $dbPassword = setting('smtp.password');
            if ($dbPassword && $dbPassword !== 'smtp_password_placeholder') {
                config([
                    'mail.mailers.smtp.host' => setting('smtp.host', config('mail.mailers.smtp.host')),
                    'mail.mailers.smtp.port' => setting('smtp.port', config('mail.mailers.smtp.port')),
                    'mail.mailers.smtp.username' => setting('smtp.username', config('mail.mailers.smtp.username')),
                    'mail.mailers.smtp.password' => $dbPassword,
                    'mail.mailers.smtp.encryption' => setting('smtp.encryption', config('mail.mailers.smtp.encryption')),
                    'mail.from.address' => setting('smtp.from_address', config('mail.from.address')),
                    'mail.from.name' => setting('smtp.from_name', config('mail.from.name')),
                ]);
            }

            // Attempt to send email
            Mail::send([], [], function ($message) use ($lead, $adminEmail, $estimatedValue) {
                $message->to($adminEmail)
                    ->subject("Cerere Ofertă Nouă - {$lead->name}")
                    ->html("
                        <h2>Ofertă nouă de supraveghere video</h2>
                        <p><strong>Nume Client:</strong> {$lead->name}</p>
                        <p><strong>Telefon:</strong> {$lead->phone}</p>
                        <p><strong>Email:</strong> {$lead->email}</p>
                        <p><strong>Localitate:</strong> {$lead->locality}</p>
                        <p><strong>Tip Locație:</strong> {$lead->location_type}</p>
                        <p><strong>Tip Proiect:</strong> " . ($lead->is_upgrade ? 'Upgrade Sistem Existent' : 'Sistem Nou') . "</p>
                        <p><strong>Număr Camere:</strong> {$lead->camera_count} camere</p>
                        <p><strong>Valoare Estimată:</strong> {$estimatedValue} RON</p>
                        <p><strong>Mesaj:</strong> " . ($lead->message ?? 'Fără mesaj.') . "</p>
                        <br>
                        <a href='" . route('admin.dashboard') . "'>Accesează CRM pentru detalii</a>
                    ");
            });

            // Record timeline activity upon mail success
            QuoteNote::create([
                'quote_request_id' => $lead->id,
                'type' => 'email_sent',
                'note' => "Notificare e-mail trimisă cu succes către administrator ({$adminEmail}).",
            ]);

        } catch (\Throwable $e) {
            // Write system failure logs in timeline
            QuoteNote::create([
                'quote_request_id' => $lead->id,
                'type' => 'system_log',
                'note' => "Trimiterea notificării e-mail către admin a eșuat. Eroare: " . $e->getMessage(),
            ]);

            // Dispatch database notification to superadmins to trigger the administrative alert bell!
            $admins = User::whereIn('role', ['superadmin', 'admin'])->get();
            
            // Build custom notification class inline
            $notificationData = [
                'title' => "Eșec trimitere email pentru lead-ul #{$lead->id}",
                'message' => "Solicitarea clientului {$lead->name} a fost salvată în CRM, dar notificarea e-mail a eșuat.",
                'lead_id' => $lead->id
            ];

            foreach ($admins as $admin) {
                $admin->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'App\Notifications\EmailDeliveryFailed',
                    'data' => json_encode($notificationData),
                ]);
            }
        }
    }
}
