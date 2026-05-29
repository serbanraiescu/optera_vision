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
use App\Http\Controllers\Public\SitemapController;

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PageCmsController;
use App\Http\Controllers\Admin\SeoManagerController;
use App\Http\Controllers\Admin\MediaLibraryController;

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

Route::get('/debug-log', function () {
    $token = request('token');
    if ($token !== 'optera_cpanel_deploy_2026') {
        abort(403, 'Acces neautorizat.');
    }
    
    $output = [];
    
    // 1. Scan cPanel standard PHP error logs
    $logPaths = [
        'Base PHP error_log' => base_path('error_log'),
        'Public PHP error_log' => public_path('error_log'),
        'Laravel Log' => storage_path('logs/laravel.log')
    ];
    
    foreach ($logPaths as $name => $path) {
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $lines = explode("\n", $content);
            $relevantLines = [];
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    $relevantLines[] = trim($line);
                }
            }
            $lastLines = array_slice($relevantLines, -15);
            $output[] = "=== {$name} ({$path}) ===\n" . implode("\n", $lastLines);
        } else {
            $output[] = "=== {$name} (Nu există) ===";
        }
    }
    
    return response(implode("\n\n", $output), 200)->header('Content-Type', 'text/plain; charset=utf-8');
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
    Route::get('/services', function () { return view('admin.services.index'); })->name('admin.services.index');
    Route::get('/projects', function () { return view('admin.projects.index'); })->name('admin.projects.index');

    // Settings Group
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearSystemCache'])->name('admin.system.cache.clear');
    Route::post('/settings/rebuild-sitemap', [SettingsController::class, 'rebuildSitemap'])->name('admin.system.sitemap.rebuild');

    // Pages CMS Group
    Route::get('/pages', [PageCmsController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/create', [PageCmsController::class, 'create'])->name('admin.pages.create');
    Route::post('/pages', [PageCmsController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{page}/edit', [PageCmsController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{page}', [PageCmsController::class, 'update'])->name('admin.pages.update');
    Route::post('/pages/{page}/duplicate', [PageCmsController::class, 'duplicate'])->name('admin.pages.duplicate');
    Route::delete('/pages/{page}', [PageCmsController::class, 'destroy'])->name('admin.pages.destroy');

    // SEO Manager Group
    Route::get('/seo', [SeoManagerController::class, 'index'])->name('admin.seo.index');
    Route::post('/seo/{type}/{id}', [SeoManagerController::class, 'updateEntitySeo'])->name('admin.seo.entity.update');

    // Media Library Group
    Route::get('/media', [MediaLibraryController::class, 'index'])->name('admin.media');
    Route::post('/media', [MediaLibraryController::class, 'store'])->name('admin.media.store');
    Route::delete('/media/{media}', [MediaLibraryController::class, 'destroy'])->name('admin.media.destroy');
});

// Dynamic Public Sitemap and Robots
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Dynamic Legal policy pages & custom SEO pages CMS fallback routing
Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
