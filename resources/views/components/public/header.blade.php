@php
    // Cache queries directly to prevent repeated DB requests on blade renders
    $servicesMenu = \Illuminate\Support\Facades\Cache::remember('active_services_menu', 3600, function () {
        return \App\Models\Service::published()->orderBy('sort_order')->get(['title', 'slug']);
    });

    $legalMenu = \Illuminate\Support\Facades\Cache::remember('published_legal_pages_menu', 3600, function () {
        return \App\Models\Page::published()->legal()->get(['title', 'slug']);
    });
@endphp

<header class="fixed top-0 w-full z-50 bg-[#f8f9fa]/90 shadow-sm border-b border-gray-200/50 backdrop-blur-md transition-all duration-300">
    <div class="flex items-center justify-between px-6 md:px-16 h-16 w-full max-w-[1280px] mx-auto" x-data="{ mobileMenuOpen: false }">
        
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 cursor-pointer group hover:opacity-90 transition-opacity">
            <div class="w-10 h-10 rounded-lg bg-[#1a3d2b] flex items-center justify-center text-white transition-transform group-hover:scale-105 duration-300">
                <svg class="w-5.5 h-5.5 text-[#a9d0b6]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-base md:text-md tracking-tight text-[#022717] leading-tight">
                    {{ setting('site.name', 'Optera Vision') }}
                </span>
                <span class="text-[8px] tracking-[0.18em] text-[#545f73] font-extrabold -mt-0.5 uppercase">
                    SUPRAVEGHERE VIDEO
                </span>
            </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="font-sans font-bold text-[11px] tracking-wider transition-colors py-2 px-1 relative {{ request()->is('/') ? 'text-[#022717]' : 'text-[#545f73] hover:text-[#022717]' }}">
                ACASĂ
                @if(request()->is('/'))
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#12422c] rounded-full"></span>
                @endif
            </a>

            <!-- Services Dropdown menu -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ url('/servicii') }}" class="font-sans font-bold text-[11px] tracking-wider transition-colors py-2 px-1 flex items-center gap-1 {{ request()->is('servicii*') ? 'text-[#022717]' : 'text-[#545f73] hover:text-[#022717]' }}">
                    SERVICII
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    @if(request()->is('servicii*'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#12422c] rounded-full"></span>
                    @endif
                </a>
                
                <!-- Services Menu Dropdown card -->
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute left-0 mt-0 w-64 bg-white border border-gray-150 rounded-2xl shadow-xl py-3 z-50" style="display: none;">
                    @foreach($servicesMenu as $menuSrv)
                        <a href="{{ url('/servicii/' . $menuSrv->slug) }}" class="block px-5 py-2.5 text-xs font-bold text-[#545f73] hover:text-[#022717] hover:bg-slate-50 transition-colors">
                            {{ $menuSrv->title }}
                        </a>
                    @endforeach
                    <div class="border-t border-slate-100 mt-2 pt-2 px-5">
                        <a href="{{ url('/servicii') }}" class="text-[10px] font-extrabold text-[#022717] hover:underline uppercase tracking-wider">Toate Serviciile &rarr;</a>
                    </div>
                </div>
            </div>

            <a href="{{ url('/proiecte') }}" class="font-sans font-bold text-[11px] tracking-wider transition-colors py-2 px-1 relative {{ request()->is('proiecte*') ? 'text-[#022717]' : 'text-[#545f73] hover:text-[#022717]' }}">
                PORTOFOLIU
                @if(request()->is('proiecte*'))
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#12422c] rounded-full"></span>
                @endif
            </a>

            <a href="{{ url('/solicita-oferta') }}" class="font-sans font-bold text-[11px] tracking-wider transition-colors py-2 px-1 relative {{ request()->is('solicita-oferta') ? 'text-[#022717]' : 'text-[#545f73] hover:text-[#022717]' }}">
                SOLICITĂ OFERTĂ
                @if(request()->is('solicita-oferta'))
                    <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#12422c] rounded-full"></span>
                @endif
            </a>

            <a href="{{ url('/contact') }}" class="bg-[#022717] text-white px-5 h-9 rounded-lg font-bold text-[10px] tracking-wider hover:bg-[#1a3d2b] active:scale-95 transition-all inline-flex items-center justify-center">
                CONTACT
            </a>
        </nav>

        <!-- Mobile menu button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-[#022717] md:hidden cursor-pointer rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg class="h-6 w-6" x-show="mobileMenuOpen" style="display: none;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Mobile Menu dropdown -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="md:hidden absolute top-16 left-0 right-0 bg-white shadow-lg border-b border-gray-150 flex flex-col p-4 gap-2 z-50" style="display: none;" @click.away="mobileMenuOpen = false">
            <a href="{{ url('/') }}" class="w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-[11px] tracking-wider {{ request()->is('/') ? 'bg-emerald-50/50 text-[#022717]' : 'text-[#545f73]' }}">ACASĂ</a>
            <a href="{{ url('/servicii') }}" class="w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-[11px] tracking-wider {{ request()->is('servicii*') ? 'bg-emerald-50/50 text-[#022717]' : 'text-[#545f73]' }}">SERVICII</a>
            <a href="{{ url('/proiecte') }}" class="w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-[11px] tracking-wider {{ request()->is('proiecte*') ? 'bg-emerald-50/50 text-[#022717]' : 'text-[#545f73]' }}">PORTOFOLIU</a>
            <a href="{{ url('/solicita-oferta') }}" class="w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-[11px] tracking-wider {{ request()->is('solicita-oferta') ? 'bg-emerald-50/50 text-[#022717]' : 'text-[#545f73]' }}">SOLICITĂ OFERTĂ</a>
            <a href="{{ url('/contact') }}" class="w-full text-left py-3 px-4 rounded-lg font-sans font-bold text-[11px] tracking-wider {{ request()->is('contact') ? 'bg-emerald-50/50 text-[#022717]' : 'text-[#545f73]' }}">CONTACT</a>
        </div>
    </div>
</header>
