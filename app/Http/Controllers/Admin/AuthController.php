<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin authentication attempts with robust security guards.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // 1. Check if the login attempts have exceeded the limit (5 failures per 5 minutes)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            $this->activityLogger->log(
                'login_blocked', 
                "Încercări de autentificare blocate temporar pentru email-ul {$request->input('email')} din cauza limitării ratei."
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // 2. Attempt to authenticate
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if the user role is authorized to access the admin panel
            if (!$user->hasRole(['superadmin', 'admin', 'operator', 'technician'])) {
                Auth::logout();
                
                $this->activityLogger->log(
                    'login_unauthorized', 
                    "Încercare de autentificare respinsă: rolul utilizatorului {$user->email} ({$user->role}) este neautorizat pentru administrare.",
                    $user->id
                );

                throw ValidationException::withMessages([
                    'email' => 'Nu aveți dreptul de a accesa panoul de administrare.',
                ]);
            }

            // Update security and metadata fields
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Log successful login
            $this->activityLogger->log(
                'login_success', 
                "Utilizatorul s-a autentificat cu succes în panoul de administrare (Rol: {$user->role}).",
                $user->id
            );

            // Reset request throttle history on success
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Handle failure: increment throttle counts and log failure audits
        RateLimiter::hit($throttleKey, 300); // lock for 5 minutes (300 seconds) if maximum reached

        $this->activityLogger->log(
            'login_failed', 
            "Eroare de autentificare în admin pentru contul: {$request->input('email')}."
        );

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Log out of the administrative session.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $this->activityLogger->log(
                'logout', 
                "Utilizatorul s-a deconectat din sesiune.",
                $user->id
            );

            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
