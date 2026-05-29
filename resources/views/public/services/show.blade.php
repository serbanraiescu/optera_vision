@extends('layouts.public')

@section('title', ($service->meta_title ?: $service->title . ' | Optera Vision'))
@section('meta_description', ($service->meta_description ?: $service->short_description))

@section('head')
    <!-- Inject structured JSON-LD Service & FAQ Schema dynamically -->
    {!! $schema !!}
@endsection

@section('content')
<main class="py-20 bg-slate-50/50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 w-full text-left">
        
        <!-- Breadcrumb & Back navigation link -->
        <div class="mb-8">
            <a href="{{ url('/servicii') }}" class="text-xs font-bold text-[#545f73] hover:text-[#022717] transition-colors flex items-center gap-1">
                &larr; Înapoi la servicii
            </a>
        </div>

        <!-- Service Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Side: Core content details (7/12 cols) -->
            <div class="lg:col-span-7 space-y-8 bg-white p-8 md:p-12 rounded-3xl border border-gray-150 shadow-sm shadow-slate-100/10">
                <!-- Icon badge -->
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl shadow-sm">
                    @if($service->icon_key === 'home')
                        🏠
                    @elseif($service->icon_key === 'smartphone')
                        📱
                    @elseif($service->icon_key === 'shield')
                        🛡️
                    @elseif($service->icon_key === 'refresh-cw')
                        🔄
                    @elseif($service->icon_key === 'tool')
                        🔧
                    @elseif($service->icon_key === 'cpu')
                        💻
                    @else
                        📹
                    @endif
                </div>

                <div class="space-y-4">
                    <h1 class="text-2xl md:text-4xl font-extrabold text-[#022717] tracking-tight">
                        {{ $service->title }}
                    </h1>
                    <p class="text-sm md:text-base text-[#545f73] leading-relaxed font-semibold">
                        {{ $service->short_description }}
                    </p>
                </div>

                <!-- Full description body text -->
                <div class="prose prose-emerald max-w-none text-xs md:text-sm text-slate-650 leading-relaxed border-t border-slate-100 pt-6">
                    {!! nl2br(e($service->full_description)) !!}
                </div>
            </div>

            <!-- Right Side: Sidebar details, FAQs, and CTA (5/12 cols) -->
            <div class="lg:col-span-5 space-y-8">
                <!-- Quick quote sidebar banner CTA -->
                <div class="bg-[#022717] text-white p-8 rounded-3xl text-left space-y-4 shadow-lg">
                    <span class="inline-block px-2.5 py-0.5 bg-white/15 text-[#a9d0b6] font-bold text-[9px] tracking-wider uppercase rounded-full">EVALUARE GRATUITĂ</span>
                    <h4 class="text-lg font-bold">Solicită un deviz pentru acest serviciu</h4>
                    <p class="text-xs text-[#a9d0b6] leading-relaxed">
                        Completează configuratorul nostru rapid pentru a primi o estimare gratuită de preț în maximum 24h.
                    </p>
                    <div class="pt-2">
                        <a href="{{ url('/solicita-oferta') }}" class="inline-flex items-center justify-center w-full bg-white text-[#022717] py-3.5 rounded-xl font-bold text-xs tracking-wider hover:bg-[#a9d0b6] transition-all">
                            CERE PREȚ ACUM
                        </a>
                    </div>
                </div>

                <!-- Dynamic Accordion FAQ Block -->
                @if(!empty($faqs))
                    <div class="bg-white border border-gray-150 p-6 md:p-8 rounded-3xl shadow-sm shadow-slate-100/10 text-left">
                        <h3 class="text-sm font-extrabold text-[#022717] tracking-wider uppercase mb-6 flex items-center gap-1.5 border-b border-slate-100 pb-3">
                            <span>❓</span> Întrebări Frecvente
                        </h3>
                        
                        <!-- Alpine FAQ List -->
                        <div class="space-y-4" x-data="{ activeFaq: null }">
                            @foreach($faqs as $index => $faq)
                                <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                    <button 
                                        @click="activeFaq === {{ $index }} ? activeFaq = null : activeFaq = {{ $index }}" 
                                        type="button" 
                                        class="w-full flex items-center justify-between text-left text-xs font-bold text-[#022717] hover:text-emerald-950 focus:outline-none py-1.5"
                                    >
                                        <span>{{ $faq['q'] }}</span>
                                        <svg 
                                            class="w-3.5 h-3.5 text-[#545f73] transition-transform duration-200" 
                                            :class="activeFaq === {{ $index }} ? 'rotate-185 text-[#022717]' : ''" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor" 
                                            stroke-width="2.5"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="activeFaq === {{ $index }}" 
                                        x-transition:enter="transition ease-out duration-200" 
                                        x-transition:enter-start="opacity-0 scale-95" 
                                        class="text-[11px] text-[#545f73] leading-relaxed mt-2" 
                                        style="display: none;"
                                    >
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
