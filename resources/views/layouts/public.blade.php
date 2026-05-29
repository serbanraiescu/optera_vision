<!DOCTYPE html>
<html lang="ro" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('seo.default_title'))</title>
    <meta name="description" content="@yield('meta_description', setting('seo.default_description'))">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Dynamic Favicon -->
    @if(setting('brand.favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('brand.favicon')) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230F3D24'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z'/%3E%3C/svg%3E">
    @endif

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom dynamic styles based on settings -->
    <style>
        :root {
            --color-primary: {{ setting('brand.primary_color', '#0F3D24') }};
            --color-primary-dark: {{ setting('brand.secondary_color', '#164E2D') }};
            --color-accent: {{ setting('brand.accent_color', '#4ADE80') }};
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    
    @yield('head')
</head>
<body class="bg-slate-50/50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Header Section -->
    <x-public.header />

    <!-- Main Content Area -->
    <main class="flex-grow pt-16">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <x-public.footer />

    <!-- STICKY MOBILE ACTION BAR -->
    <div class="fixed bottom-0 left-0 z-45 w-full bg-white/95 backdrop-blur-md border-t border-slate-100 md:hidden flex items-center justify-around py-3 px-4 shadow-xl shadow-slate-900/10">
        <a href="tel:{{ str_replace(' ', '', setting('company.phone')) }}" class="flex flex-col items-center justify-center gap-1 group text-slate-600 active:text-emerald-900 focus:outline-none">
            <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm group-active:scale-95 transition-transform">
                <svg class="w-4.5 h-4.5 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-tight">Sună Acum</span>
        </a>
        <a href="https://wa.me/{{ str_replace('+', '', str_replace(' ', '', setting('company.whatsapp'))) }}" target="_blank" rel="noopener" class="flex flex-col items-center justify-center gap-1 group text-slate-600 active:text-emerald-900 focus:outline-none">
            <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100/50 flex items-center justify-center shadow-sm group-active:scale-95 transition-transform">
                <svg class="w-5 h-5 text-emerald-700" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.517 2.266 2.27 3.51 5.282 3.51 8.485-.006 6.66-5.343 11.997-11.958 11.997-2.005-.003-3.973-.505-5.724-1.46L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.003-2.637-1.03-5.114-2.908-6.993C16.65 1.87 14.17 .837 11.53.837c-5.44 0-9.867 4.424-9.871 9.87-.001 1.701.455 3.361 1.32 4.816l-.995 3.636 3.737-.98 1.335.795zm11.303-7.794c-.3-.15-1.772-.875-2.046-.975-.276-.1-.476-.15-.676.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.797-1.49-1.78-1.666-2.08-.175-.3-.02-.463.13-.612.135-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.676-1.625-.926-2.225-.244-.588-.492-.508-.676-.518-.174-.01-.374-.012-.574-.012-.2 0-.526.075-.802.375-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.11 3.224 5.116 4.525.715.31 1.273.495 1.707.633.718.228 1.37.195 1.887.118.577-.087 1.772-.725 2.022-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.275-.275-.575-.425z" />
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-tight">WhatsApp</span>
        </a>
        <a href="{{ url('/solicita-oferta') }}" class="flex flex-col items-center justify-center gap-1 group text-slate-600 active:text-emerald-900 focus:outline-none">
            <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm group-active:scale-95 transition-transform">
                <svg class="w-4.5 h-4.5 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-tight">Cere Ofertă</span>
        </a>
    </div>

    <!-- LIGHTWEIGHT CUSTOM COOKIE CONSENT BANNER -->
    <div x-data="cookieConsent()" x-init="init()" x-show="visible" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-12" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-12" class="fixed bottom-6 right-6 z-50 max-w-md w-[calc(100vw-3rem)] bg-white border border-slate-150 rounded-2xl shadow-2xl p-6" style="display: none;">
        <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center border border-emerald-100 text-emerald-800 shrink-0">
                    🍪
                </div>
                <div class="flex flex-col">
                    <h5 class="text-sm font-bold text-slate-900 leading-snug">Respectăm confidențialitatea</h5>
                    <p class="text-xs text-slate-500 leading-relaxed mt-1">
                        Utilizăm cookie-uri pentru a vă asigura o experiență optimă de navigare. Apăsând „Acceptă Tot”, sunteți de acord cu utilizarea acestora conform <a href="{{ url('/politica-cookies') }}" class="underline text-emerald-850 font-semibold hover:text-emerald-950">Politicii de Cookies</a>.
                    </p>
                </div>
            </div>

            <!-- Granular preferences selection (Collapsible) -->
            <div x-show="showPreferences" x-collapse class="border-t border-slate-100 pt-4 flex flex-col gap-3" style="display: none;">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-800">Sistem (Necesare)</span>
                        <span class="text-[10px] text-slate-400">Funcționarea de bază a site-ului.</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full">Permanent</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-800">Analitice (Statistici)</span>
                        <span class="text-[10px] text-slate-400">Ne ajută să înțelegem cum interacționați cu site-ul.</span>
                    </div>
                    <input type="checkbox" x-model="prefs.analytics" class="w-4 h-4 text-emerald-800 rounded border-slate-300 focus:ring-emerald-700">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-800">Marketing (Publicitate)</span>
                        <span class="text-[10px] text-slate-400">Personalizarea reclamelor promoționale.</span>
                    </div>
                    <input type="checkbox" x-model="prefs.marketing" class="w-4 h-4 text-emerald-800 rounded border-slate-300 focus:ring-emerald-700">
                </div>
            </div>

            <!-- Buttons bar -->
            <div class="flex flex-col gap-2 pt-2 border-t border-slate-100">
                <div class="flex gap-2">
                    <button @click="acceptAll()" type="button" class="flex-1 inline-flex items-center justify-center px-4 h-10 text-xs font-bold text-white bg-emerald-850 hover:bg-emerald-950 rounded-xl transition-colors shadow-sm">
                        Acceptă Tot
                    </button>
                    <button @click="rejectAll()" type="button" class="flex-1 inline-flex items-center justify-center px-4 h-10 text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors border border-slate-200/50">
                        Refuză
                    </button>
                </div>
                <button @click="showPreferences = !showPreferences" type="button" class="text-center text-[10px] font-bold text-slate-500 hover:text-slate-850 py-1 transition-colors">
                    <span x-text="showPreferences ? 'Ascunde Preferințe' : 'Personalizează Preferințe'"></span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function cookieConsent() {
            return {
                visible: false,
                showPreferences: false,
                prefs: {
                    analytics: false,
                    marketing: false
                },
                init() {
                    setTimeout(() => {
                        const savedConsent = localStorage.getItem('cookie_consent_choice');
                        if (!savedConsent) {
                            this.visible = true;
                        } else {
                            // Apply GTM / GA placeholders here if consent exists
                            const consent = JSON.parse(savedConsent);
                            this.applyConsent(consent);
                        }
                    }, 1500); // delay banner showing for premium user experience
                },
                acceptAll() {
                    const consent = { necessary: true, analytics: true, marketing: true };
                    localStorage.setItem('cookie_consent_choice', JSON.stringify(consent));
                    this.applyConsent(consent);
                    this.visible = false;
                },
                rejectAll() {
                    const consent = { necessary: true, analytics: false, marketing: false };
                    localStorage.setItem('cookie_consent_choice', JSON.stringify(consent));
                    this.applyConsent(consent);
                    this.visible = false;
                },
                savePreferences() {
                    const consent = { necessary: true, ...this.prefs };
                    localStorage.setItem('cookie_consent_choice', JSON.stringify(consent));
                    this.applyConsent(consent);
                    this.visible = false;
                },
                applyConsent(consent) {
                    // Placeholder trigger for Google Tag Manager Consent Mode in V2
                    console.log('Applying Cookie Consents:', consent);
                }
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
