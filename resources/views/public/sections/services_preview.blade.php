@php
    $headerBlock = $blocks->where('block_key', 'section_header')->first();
    $headerContent = $headerBlock ? $headerBlock->content : [];
    
    // Fetch featured published services from high-performance cache to avoid N+1 query loops
    $activeServices = \Illuminate\Support\Facades\Cache::remember('home_services_preview', 3600, function () {
        return \App\Models\Service::published()->where('is_featured', true)->orderBy('sort_order')->take(4)->get();
    });
@endphp

<section class="py-24 px-6 md:px-16 bg-[#f8f9fa] border-t border-slate-100" id="servicii">
    <div class="max-w-[1280px] mx-auto w-full">
        <!-- Section Header -->
        <div class="mb-16 text-left flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-4">
                <span class="font-bold text-xs tracking-widest text-[#022717] uppercase">SERVICII ELITE</span>
                <h2 class="text-3xl font-extrabold text-[#022717] tracking-tight leading-tight">
                    {{ $headerContent['title'] ?? 'Servicii Profesionale de Supraveghere' }}
                </h2>
                <p class="text-[#545f73] max-w-xl text-xs md:text-sm leading-relaxed">
                    {{ $headerContent['subtitle'] ?? 'Soluții personalizate adaptate perfect nevoilor tale în Câmpulung Moldovenesc și Bucovina.' }}
                </p>
            </div>
            <a href="{{ url('/servicii') }}" class="text-xs font-bold text-[#022717] hover:underline uppercase tracking-wider shrink-0 pb-1">
                Vezi toate serviciile &rarr;
            </a>
        </div>

        <!-- Service Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
            @foreach($activeServices as $srv)
                <div class="bg-white p-6 rounded-2xl border border-gray-150 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div>
                        <!-- Badge Dynamic Icon based on key -->
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                            @if($srv->icon_key === 'home')
                                🏠
                            @elseif($srv->icon_key === 'smartphone')
                                📱
                            @elseif($srv->icon_key === 'shield')
                                🛡️
                            @elseif($srv->icon_key === 'refresh-cw')
                                🔄
                            @else
                                📹
                            @endif
                        </div>
                        <h3 class="text-md font-bold text-[#022717] mb-2">{{ $srv->title }}</h3>
                        <p class="text-xs text-[#545f73] leading-relaxed mb-6">
                            {{ $srv->short_description }}
                        </p>
                    </div>
                    <a href="{{ url('/servicii/' . $srv->slug) }}" class="text-[10px] font-extrabold text-[#022717] hover:underline uppercase tracking-wider flex items-center gap-1">
                        Detalii Serviciu
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
