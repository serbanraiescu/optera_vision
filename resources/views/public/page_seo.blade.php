@extends('layouts.public')

@section('title', ($page->meta_title ?? $page->title) . ' | Optera Vision')
@section('meta_description', $page->meta_description ?? setting('seo.default_description'))

@section('content')
<!-- Regional Hero Section -->
<section class="relative overflow-hidden min-h-[50vh] flex items-center px-6 md:px-16 py-16 bg-gradient-to-br from-[#f8f9fa] via-white to-[#a9d0b6]/10 border-b border-slate-100">
    <div class="max-w-[1280px] mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Headline details -->
        <div class="lg:col-span-7 text-left space-y-6">
            <span class="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[9px] tracking-wider uppercase">
                Servicii Supraveghere Video Locale
            </span>
            <h1 class="font-extrabold text-4xl md:text-5xl text-[#022717] tracking-tight leading-tight">
                {{ $page->title }}
            </h1>
            <p class="font-sans text-sm md:text-md text-[#545f73] leading-relaxed max-w-xl">
                Optera Vision oferă servicii profesionale complete de montaj, cablare și mentenanță pentru sisteme de supraveghere video de înaltă rezoluție, adaptate specific cerințelor din zona dumneavoastră.
            </p>
            <div class="flex items-center gap-6 pt-2">
                <a href="#configurator" class="bg-[#022717] text-white px-6 h-12 rounded-xl font-bold text-xs tracking-wider flex items-center justify-center gap-2 hover:bg-[#1a3d2b] hover:shadow-lg transition-all active:scale-95 uppercase">
                    Configurați preț local &darr;
                </a>
                <a href="tel:{{ str_replace(' ', '', setting('company.phone')) }}" class="font-bold text-xs text-[#022717] hover:underline flex items-center gap-1.5">
                    Sunați acum: {{ setting('company.phone') }}
                </a>
            </div>
        </div>

        <!-- Right Side: Trust badge / Visual -->
        <div class="lg:col-span-5 flex justify-center">
            <div class="bg-white border border-gray-150 rounded-3xl p-6 shadow-md max-w-sm space-y-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#022717] flex items-center justify-center text-xl shrink-0">
                    🛡️
                </div>
                <div class="space-y-1 text-left">
                    <h4 class="font-bold text-sm text-[#022717]">Tehnicieni Certificați în Bucovina</h4>
                    <p class="font-sans text-xs text-[#545f73] leading-relaxed">
                        Instalăm exclusiv echipamente profesionale autorizate, cu stocare securizată conform reglementărilor legale și configurare sigură pentru control de pe mobil.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content and Wizard split section -->
<section id="configurator" class="py-16 px-6 md:px-16 bg-white">
    <div class="max-w-[1280px] mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Landing Page SEO dynamic content -->
        <div class="lg:col-span-7 space-y-8 text-left">
            <div class="prose prose-emerald max-w-none font-sans text-xs md:text-sm text-slate-650 leading-relaxed space-y-6">
                {!! $page->content !!}
            </div>

            <!-- Custom local active services list -->
            <div class="border-t border-slate-100 pt-8 space-y-6">
                <h3 class="text-md font-extrabold text-[#022717] uppercase tracking-wider">Ce servicii video instalăm local:</h3>
                
                @php
                    $localServices = \Illuminate\Support\Facades\Cache::remember('local_seo_services_list', 3600, function() {
                        return \App\Models\Service::published()->orderBy('sort_order')->take(4)->get();
                    });
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($localServices as $srv)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex gap-3 hover:shadow-sm transition-all duration-200">
                            <span class="text-emerald-800 text-lg">✓</span>
                            <div class="space-y-0.5">
                                <h5 class="font-bold text-xs text-[#022717]">{{ $srv->title }}</h5>
                                <p class="text-[10px] text-[#545f73] font-medium leading-relaxed">{{ $srv->short_description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Premium local multi-step quote wizard card -->
        <div class="lg:col-span-5 bg-[#f8f9fa] border border-gray-150 rounded-3xl p-8 shadow-sm space-y-6 sticky top-24">
            <div class="space-y-1">
                <span class="inline-block py-1 px-2 bg-emerald-50 text-[#022717] rounded-md font-bold text-[8px] tracking-wider uppercase">
                    Configurator Oferte
                </span>
                <h4 class="font-bold text-md text-[#022717]">Cereți preț instant</h4>
                <p class="text-[10px] text-[#545f73]">Răspundeți la 4 întrebări rapide pentru a obține o estimare exactă</p>
            </div>
            
            <x-public.quote-configurator />
        </div>
    </div>
</section>
@endsection
