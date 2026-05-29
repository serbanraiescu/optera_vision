@extends('layouts.public')

@section('title', 'Servicii Supraveghere Video | Optera Vision')
@section('meta_description', 'Descoperiți gama completă de servicii de supraveghere video oferite de Optera Vision în Bucovina. Instalare camere interior/exterior, DVR/NVR și configurare.')

@section('content')
<main class="py-20 bg-slate-50/50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 w-full text-left">
        <!-- Header -->
        <div class="max-w-2xl mb-16 space-y-4">
            <span class="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[9px] tracking-wider uppercase">
                SERVICII AUTORIZATE
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-[#022717] tracking-tight leading-tight">
                Serviciile Noastre de Securitate
            </h1>
            <p class="text-[#545f73] text-sm md:text-base leading-relaxed">
                Optera Vision oferă exclusiv sisteme de monitorizare video profesionale, configurări curate, diagnosticări rapide și upgrade-uri inteligente pentru case și spații comerciale în Câmpulung Moldovenesc și Bucovina.
            </p>
        </div>

        <!-- Services Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($services as $srv)
                <div class="bg-white p-8 rounded-2xl border border-gray-150 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300 group">
                    <div>
                        <!-- Dynamic Badge Icon -->
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-300">
                            @if($srv->icon_key === 'home')
                                🏠
                            @elseif($srv->icon_key === 'smartphone')
                                📱
                            @elseif($srv->icon_key === 'shield')
                                🛡️
                            @elseif($srv->icon_key === 'refresh-cw')
                                🔄
                            @elseif($srv->icon_key === 'tool')
                                🔧
                            @elseif($srv->icon_key === 'cpu')
                                💻
                            @else
                                📹
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-[#022717] mb-3 group-hover:text-emerald-950 transition-colors">
                            {{ $srv->title }}
                        </h3>
                        <p class="text-xs md:text-sm text-[#545f73] leading-relaxed mb-8">
                            {{ $srv->short_description }}
                        </p>
                    </div>
                    <a href="{{ url('/servicii/' . $srv->slug) }}" class="text-[10px] font-extrabold text-[#022717] hover:underline uppercase tracking-wider flex items-center gap-1">
                        Află mai multe detalii
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Static CTA Section -->
        <div class="bg-[#022717] text-white p-10 md:p-16 rounded-3xl mt-16 text-center space-y-6 relative overflow-hidden shadow-lg">
            <div class="z-10 relative max-w-2xl mx-auto space-y-4">
                <span class="inline-block px-3 py-1 bg-white/10 text-[#a9d0b6] font-bold text-[9px] tracking-widest uppercase rounded-full">EVALUARE GRATUITĂ</span>
                <h3 class="text-2xl md:text-3xl font-bold">Nu ești sigur ce sistem se potrivește spațiului tău?</h3>
                <p class="text-xs md:text-sm text-[#a9d0b6] leading-relaxed opacity-90">
                    Inginerii noștri oferă consultanță tehnică gratuită și evaluare la fața locului în Câmpulung Moldovenesc, Suceava, Gura Humorului și zonele învecinate.
                </p>
                <div class="pt-4">
                    <a href="{{ url('/solicita-oferta') }}" class="inline-flex items-center justify-center bg-white text-[#022717] px-8 h-12 rounded-xl font-bold text-xs tracking-wider hover:bg-[#a9d0b6] hover:scale-105 transition-all">
                        CERE OFERTĂ PERSONALIZATĂ
                    </a>
                </div>
            </div>
            
            <!-- Abstract decorative right image with code mix blend from imported React UI -->
            <div class="absolute right-0 top-0 bottom-0 overflow-hidden select-none pointer-events-none opacity-10 w-1/3">
                <img class="h-full w-full object-cover mix-blend-screen" loading="lazy" width="200" height="200" alt="Placa de circuite" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBkvFoy_VgB2crE3iE7YNYhYah8rBmuP1rbJYT02WdndorJU7Sl7papuEzYcnh_wZHFu4Imsr23NA4UeVAl6gdUMuIkNy9DfBs8_wmaUr6sfPdXXHJdBiprUwKEkmbpSjITD8PUKT2BeL43Uw5frWTH0C7kJ7qglIHljYGCeDJVSYJeS8DXpeBcX740lLpA9I0PQKcAr3qUtzMq040E4_oEvk5mwKYmCJQfN5LwDfn06ctRr8NlayWR1m98uR5H8rpgoPXBxbFBupoz">
            </div>
        </div>
    </div>
</main>
@endsection
