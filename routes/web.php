<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
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

// Dynamic Legal policy pages & custom SEO pages CMS fallback routing
Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');


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
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Future CRM, CMS and System Modules (Placeholders for routing compile safety)
    Route::get('/quotes', function () { return 'Quotes List'; })->name('admin.quotes.index');
    Route::get('/services', function () { return 'Services List'; })->name('admin.services.index');
    Route::get('/projects', function () { return 'Projects List'; })->name('admin.projects.index');
    Route::get('/pages', function () { return 'Pages CMS'; })->name('admin.pages.index');
    Route::get('/settings', function () { return 'Site Settings'; })->name('admin.settings.index');
});
