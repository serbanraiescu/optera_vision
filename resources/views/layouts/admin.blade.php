<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Optera Vision</title>
    
    <!-- Dynamic Favicon -->
    @if(setting('brand.favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('brand.favicon')) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230F3D24'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z'/%3E%3C/svg%3E">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- HSL Curated forest green styles matching the site's dynamic branding -->
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
<body class="bg-slate-50/50 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">

    <!-- Sidebar component -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static top-0 left-0 z-30 w-72 h-screen bg-white border-r border-slate-100 flex flex-col shrink-0 transition-transform duration-300 ease-in-out">
        
        <!-- Sidebar Branding -->
        <div class="h-20 border-b border-slate-100 flex items-center px-6 gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100/50">
                <svg class="w-4 h-4 text-emerald-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-tight text-slate-900 leading-none">OPTERA VISION</span>
                <span class="text-[9px] font-extrabold tracking-widest text-emerald-700 leading-none mt-1 uppercase">Admin Panel</span>
            </div>
        </div>

        <!-- Sidebar Navigation Menu Links -->
        <nav class="flex-grow p-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl {{ request()->routeIs('admin.dashboard') ? 'text-emerald-800 bg-emerald-50/50 border border-emerald-100/30' : 'text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent' }} transition-all">
                <span class="text-sm">📊</span>
                Tablou de Bord
            </a>
            
            <a href="{{ route('admin.quotes') }}" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl {{ request()->routeIs('admin.quotes*') ? 'text-emerald-800 bg-emerald-50/50 border border-emerald-100/30' : 'text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent' }} transition-all">
                <span class="text-sm">📞</span>
                Cereri Ofertă (CRM)
            </a>

            @if(auth()->user()->role !== 'technician')
            <a href="{{ route('admin.clients') }}" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl {{ request()->routeIs('admin.clients*') ? 'text-emerald-800 bg-emerald-50/50 border border-emerald-100/30' : 'text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent' }} transition-all">
                <span class="text-sm">👥</span>
                Registru Clienți
            </a>
            @endif

            <a href="#" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                <span class="text-sm">🛠️</span>
                Servicii <span class="ml-auto text-[10px] font-extrabold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full">F4</span>
            </a>

            <a href="#" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                <span class="text-sm">📂</span>
                Proiecte Portofoliu <span class="ml-auto text-[10px] font-extrabold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full">F4</span>
            </a>

            <a href="#" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                <span class="text-sm">📄</span>
                Pagini CMS <span class="ml-auto text-[10px] font-extrabold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full">F4</span>
            </a>

            <a href="#" class="flex items-center gap-3.5 px-4 h-11 text-xs font-bold rounded-xl text-slate-550 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                <span class="text-sm">⚙️</span>
                Setări Brand & Site <span class="ml-auto text-[10px] font-extrabold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full">F4</span>
            </a>
        </nav>

        <!-- Sidebar Footer Session Profile -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-800 truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                <span class="text-[9px] font-semibold text-emerald-850 uppercase tracking-widest mt-0.5">{{ auth()->user()->role }}</span>
            </div>
            
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200/50 hover:bg-red-50 hover:text-red-650 hover:border-red-100 flex items-center justify-center transition-colors focus:outline-none" title="Deconectare">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-h-screen">
        
        <!-- Admin Header bar -->
        <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-6 md:px-10 shrink-0">
            
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-50 border border-slate-200/40">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Section Title -->
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider hidden md:block">
                @yield('section_title', 'Panou Control')
            </h2>

            <!-- Header tools -->
            <div class="flex items-center gap-6">
                <!-- Notifications Bell -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="w-10 h-10 rounded-full hover:bg-slate-50 flex items-center justify-center border border-slate-200/30 text-slate-650 transition-colors focus:outline-none relative">
                        🔔
                        <!-- Notification indicator dot -->
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown panel -->
                    <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 mt-3 w-80 bg-white border border-slate-150 rounded-2xl shadow-xl py-3 x-50 z-50">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">Notificări</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('admin.notifications.read-all') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[9px] font-bold text-emerald-700 hover:text-emerald-950 focus:outline-none">
                                        Citește tot
                                    </button>
                                </form>
                            @else
                                <span class="text-[9px] font-bold text-slate-400">0 noi</span>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                            @forelse(auth()->user()->unreadNotifications as $notif)
                                @php
                                    $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
                                    $leadId = $data['lead_id'] ?? null;
                                @endphp
                                <div class="hover:bg-slate-50/50 transition-colors flex items-start gap-2 p-3">
                                    <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="flex-grow text-left">
                                        @csrf
                                        <button type="submit" class="w-full text-left focus:outline-none">
                                            @if($leadId)
                                                <a href="{{ route('admin.quotes.show', $leadId) }}" class="block">
                                                    <span class="text-[11px] text-slate-800 font-bold block">{{ $data['title'] ?? 'Notificare' }}</span>
                                                    <span class="text-[10px] text-slate-500 leading-tight block mt-0.5">{{ $data['message'] ?? '' }}</span>
                                                </a>
                                            @else
                                                <span class="text-[11px] text-slate-800 font-bold block">{{ $data['title'] ?? 'Notificare' }}</span>
                                                <span class="text-[10px] text-slate-500 leading-tight block mt-0.5">{{ $data['message'] ?? '' }}</span>
                                            @endif
                                            <span class="text-[8px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="inline pt-0.5">
                                        @csrf
                                        <button type="submit" class="w-4 h-4 rounded-full flex items-center justify-center text-[9px] text-slate-450 hover:bg-emerald-50 hover:text-emerald-800" title="Marchează ca citit">
                                            ✓
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="py-8 text-center text-xs text-slate-450 font-medium">
                                    Nu aveți notificări necitite.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Last Login Audit Info -->
                <div class="text-right hidden sm:flex flex-col gap-0.5">
                    <span class="text-[10px] text-slate-450 font-semibold leading-none">Ultima autentificare</span>
                    <span class="text-[10px] text-slate-600 font-bold leading-none mt-1">
                        @if(auth()->user()->last_login_at)
                            {{ auth()->user()->last_login_at->format('d.m.Y H:i') }} (IP: {{ auth()->user()->last_login_ip }})
                        @else
                            Prima autentificare
                        @endif
                    </span>
                </div>
            </div>
        </header>

        <!-- Main Dashboard View Container -->
        <main class="flex-grow p-6 md:p-10">
            @yield('content')
        </main>
        
        <!-- Footer bar -->
        <footer class="h-14 border-t border-slate-150/40 bg-white flex items-center justify-between px-6 md:px-10 shrink-0 text-[10px] font-semibold text-slate-400">
            <span>&copy; {{ date('Y') }} Optera Vision. Panou Securizat.</span>
            <span>v1.0.0 (Phase 1 Foundation)</span>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
