<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\QuoteController;
use App\Http\Controllers\Public\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes - Optera Vision Presentation Website & Custom CRM
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC WEBSITE SYSTEM
// ==========================================
Route::get('/deploy-setup', function () {
    $token = request('token');
    $secureToken = 'optera_cpanel_deploy_2026';

    // 1. Core security checks: token validation
    if ($token !== $secureToken) {
        abort(403, 'Acces neautorizat. Token de securitate invalid.');
    }

    // 2. Strict live deployment security restriction:
    // If the database has already been successfully migrated and seeded,
    // restrict this endpoint exclusively to authenticated superadmin accounts.
    $isMigrated = false;
    try {
        $isMigrated = \Illuminate\Support\Facades\Schema::hasTable('users') 
            && \Illuminate\Support\Facades\Schema::hasTable('settings') 
            && \App\Models\User::where('role', 'superadmin')->exists();
    } catch (\Throwable $e) {
        $isMigrated = false;
    }

    if ($isMigrated) {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Acces refuzat. După instalarea inițială, această rută este securizată și poate fi accesată exclusiv de un cont de SuperAdmin autentificat.');
        }
    }

    $output = [];

    try {
        // 3. Smart migration runner
        if (!$isMigrated) {
            // Wipes and runs seeds on fresh installation
            $output[] = "--> [Instalare Inițială] Rulare Migrări Complete și Seeder...";
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
                '--force' => true,
                '--seed' => true
            ]);
            $output[] = "Succes:\n" . \Illuminate\Support\Facades\Artisan::output();
        } else {
            // Runs incremental migrations only, safeguarding live records
            $output[] = "--> [Actualizare Sistem] Rulare Migrări Incrementale...";
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--force' => true
            ]);
            $output[] = "Succes:\n" . \Illuminate\Support\Facades\Artisan::output();
        }

        // 4. Clear all configuration, cache, and view caches
        $output[] = "--> Curățare cache-uri...";
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $output[] = "Succes: Cache-urile au fost curățate.";

        // 5. Automatically establish storage symlink
        $output[] = "--> Creare legătură simbolică storage (symlink)...";
        $target = '/home/optera_vision/storage/app/public';
        $shortcut = '/home/public_html/storage';

        if (file_exists($shortcut)) {
            if (is_link($shortcut)) {
                $output[] = "Info: Symlink-ul de storage există deja.";
            } else {
                $output[] = "Atenție: Calea {$shortcut} există și nu este un link simbolic.";
            }
        } else {
            if (@symlink($target, $shortcut)) {
                $output[] = "Succes: Symlink creat cu succes între {$target} și {$shortcut}!";
            } else {
                $output[] = "Eroare: Nu s-a putut crea symlink-ul automat. Verificați permisiunile cPanel.";
            }
        }

        return response(implode("\n", $output), 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');

    } catch (\Throwable $e) {
        $output[] = "Eroare critică în timpul deploy-ului: " . $e->getMessage();
        return response(implode("\n", $output), 500)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/servicii', [ServiceController::class, 'index'])->name('services.index');
Route::get('/servicii/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/proiecte', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/proiecte/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/solicita-oferta', [QuoteController::class, 'index'])->name('quote.request');
Route::post('/solicita-oferta/trimite', [QuoteController::class, 'submit'])->name('quote.submit');

Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');




// ==========================================
// 2. ADMIN PANEL AUTHENTICATION
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});


// ==========================================
// 3. PROTECTED ADMIN PORTAL & MINI CRM
// ==========================================
Route::middleware(['auth', 'role:superadmin,admin,operator,technician'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // CRM Quote Board
    Route::get('/quotes', [AdminQuoteController::class, 'index'])->name('admin.quotes');
    Route::get('/quotes/export', [AdminExportController::class, 'exportCsv'])->name('admin.quotes.export');
    Route::get('/quotes/{quoteRequest}', [AdminQuoteController::class, 'show'])->name('admin.quotes.show');
    Route::post('/quotes/{quoteRequest}/status', [AdminQuoteController::class, 'updateStatus'])->name('admin.quotes.status');
    Route::post('/quotes/{quoteRequest}/note', [AdminQuoteController::class, 'addNote'])->name('admin.quotes.note');
    Route::post('/quotes/{quoteRequest}/assign', [AdminQuoteController::class, 'assignUser'])->name('admin.quotes.assign');
    Route::post('/quotes/{quoteRequest}/important', [AdminQuoteController::class, 'toggleImportant'])->name('admin.quotes.important');
    Route::delete('/quotes/{quoteRequest}', [AdminQuoteController::class, 'destroy'])->name('admin.quotes.destroy');

    // Clients Registry
    Route::get('/clients', [AdminClientController::class, 'index'])->name('admin.clients');
    Route::post('/quotes/{quoteRequest}/convert-client', [AdminClientController::class, 'linkFromLead'])->name('admin.clients.link');

    // Notifications Mark as Read
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsRead'])->name('admin.notifications.read-all');
    Route::post('/notifications/{id}/mark-read', [DashboardController::class, 'markNotificationRead'])->name('admin.notifications.read');
    
    // Future CMS and System Modules (Placeholders for routing compile safety)
    Route::get('/services', function () { return 'Services List'; })->name('admin.services.index');
    Route::get('/projects', function () { return 'Projects List'; })->name('admin.projects.index');
    Route::get('/pages', function () { return 'Pages CMS'; })->name('admin.pages.index');
    Route::get('/settings', function () { return 'Site Settings'; })->name('admin.settings.index');
});

// Dynamic Legal policy pages & custom SEO pages CMS fallback routing
Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
