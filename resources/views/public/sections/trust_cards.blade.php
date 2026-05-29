@php
    $cardBlocks = $blocks->where('block_key', 'trust_card_item')->values();
@endphp

<section class="py-24 px-6 md:px-16 bg-white" id="despre">
    <div class="max-w-[1280px] mx-auto w-full">
        <!-- Section Header -->
        <div class="mb-16 text-left">
            <h2 class="text-3xl font-extrabold text-[#022717] tracking-tight mb-4">
                Securitate fără compromisuri
            </h2>
            <p class="text-[#545f73] max-w-2xl text-xs md:text-sm leading-relaxed">
                Oferim soluții complete de supraveghere video, adaptate nevoilor specifice ale clienților noștri din Câmpulung Moldovenesc și Bucovina.
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
            @foreach($cardBlocks as $idx => $cardBlock)
                @php
                    $card = $cardBlock->content;
                    $title = $card['title'] ?? '';
                    $description = $card['description'] ?? '';
                    $icon = $card['icon'] ?? 'shield';
                @endphp

                @if($idx === 0)
                    <!-- Card 1 - Montaj Profesionist (Wide 2-col) -->
                    <div class="md:col-span-2 bg-[#f8f9fa] p-8 md:p-10 rounded-2xl border border-gray-150 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                                <span class="text-lg">🛠️</span>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-[#022717] mb-3">{{ $title }}</h3>
                            <p class="text-xs md:text-sm text-[#545f73] leading-relaxed mb-6">
                                {{ $description }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-white border border-gray-200/50 rounded-full font-extrabold text-[8px] text-[#545f73] tracking-wider uppercase">CURĂȚENIE</span>
                            <span class="px-3 py-1 bg-white border border-gray-200/50 rounded-full font-extrabold text-[8px] text-[#545f73] tracking-wider uppercase">PRECIZIE</span>
                        </div>
                    </div>
                @elseif($idx === 1)
                    <!-- Card 2 - Acces Mobil (1-col) -->
                    <div class="bg-[#f8f9fa] p-8 rounded-2xl border border-gray-150 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                                <span class="text-lg">📱</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#022717] mb-3">{{ $title }}</h3>
                            <p class="text-xs text-[#545f73] leading-relaxed">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                @elseif($idx === 2)
                    <!-- Card 3 - Service & mentenanta (1-col) -->
                    <div class="bg-[#f8f9fa] p-8 rounded-2xl border border-gray-150 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-[#022717] text-[#a9d0b6] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform">
                                <span class="text-lg">🛡️</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#022717] mb-3">{{ $title }}</h3>
                            <p class="text-xs text-[#545f73] leading-relaxed">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                @elseif($idx === 3)
                    <!-- Card 4 - Upgrade (Wide Full-row 4-col) -->
                    <div class="md:col-span-4 flex flex-col md:flex-row items-center gap-8 bg-[#022717] text-white p-10 md:p-12 rounded-3xl overflow-hidden relative shadow-lg">
                        <div class="flex-1 z-10">
                            <span class="inline-block px-3 py-1 bg-white/10 text-[#a9d0b6] font-bold text-[9px] tracking-widest uppercase rounded-full mb-4">
                                UPGRADE INTELIGENT
                            </span>
                            <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ $title }}</h3>
                            <p class="text-xs md:text-sm text-[#a9d0b6] leading-relaxed max-w-xl opacity-90">
                                {{ $description }}
                            </p>
                            <a href="{{ url('/solicita-oferta') }}" class="inline-flex items-center justify-center mt-8 bg-white text-[#022717] px-6 h-11 rounded-xl font-bold text-xs tracking-wider hover:bg-[#a9d0b6] hover:scale-105 transition-all cursor-pointer">
                                SOLICITĂ EVALUARE GRATUITĂ
                            </a>
                        </div>
                        
                        <!-- Abstract decorative right image with code mix blend from imported React UI -->
                        <div class="hidden md:block w-1/3 absolute right-0 top-0 bottom-0 overflow-hidden select-none pointer-events-none">
                            <img class="h-full w-full object-cover opacity-15 mix-blend-screen" loading="lazy" width="300" height="200" alt="Placa de circuite si circuite integrate" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBkvFoy_VgB2crE3iE7YNYhYah8rBmuP1rbJYT02WdndorJU7Sl7papuEzYcnh_wZHFu4Imsr23NA4UeVAl6gdUMuIkNy9DfBs8_wmaUr6sfPdXXHJdBiprUwKEkmbpSjITD8PUKT2BeL43Uw5frWTH0C7kJ7qglIHljYGCeDJVSYJeS8DXpeBcX740lLpA9I0PQKcAr3qUtzMq040E4_oEvk5mwKYmCJQfN5LwDfn06ctRr8NlayWR1m98uR5H8rpgoPXBxbFBupoz">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
