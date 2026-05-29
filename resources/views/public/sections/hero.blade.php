@php
    $block = $blocks->where('block_key', 'hero_headline')->first();
    $content = $block ? $block->content : [];
@endphp

<section class="relative overflow-hidden min-h-[75vh] flex items-center px-6 md:px-16 py-16 bg-gradient-to-br from-[#f8f9fa] via-white to-[#a9d0b6]/10">
    <div class="max-w-[1280px] mx-auto w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <!-- Text details -->
        <div class="z-10 text-left">
            <span class="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[9px] tracking-wider mb-6 uppercase">
                CAMERE SUPRAVEGHERE VIDEO
            </span>
            <h1 class="font-extrabold text-4xl md:text-5xl lg:text-5xl text-[#022717] tracking-tight leading-tight mb-6">
                {{ $content['title'] ?? 'Sisteme de supraveghere video pentru locuințe și afaceri' }}
            </h1>
            <p class="font-sans text-sm md:text-md text-[#545f73] leading-relaxed mb-10 max-w-lg">
                {{ $content['subtitle'] ?? 'Montaj curat, configurare completă și acces de pe telefon pentru clienți din Câmpulung Moldovenesc și zonele apropiate din Bucovina.' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ url('/solicita-oferta') }}" class="bg-[#022717] text-white px-8 py-4 rounded-xl font-bold text-xs tracking-wider flex items-center justify-center gap-2 hover:bg-[#1a3d2b] hover:shadow-lg hover:shadow-emerald-950/10 transition-all active:scale-95 cursor-pointer uppercase">
                    {{ $content['cta_text'] ?? 'Solicită ofertă' }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="tel:{{ str_replace(' ', '', setting('company.phone')) }}" class="border border-[#022717] text-[#022717] px-8 py-4 rounded-xl font-bold text-xs tracking-wider flex items-center justify-center gap-2 hover:bg-[#022717]/5 transition-all cursor-pointer uppercase">
                    {{ $content['phone_text'] ?? 'Sună acum' }}
                </a>
            </div>
        </div>

        <!-- Right Side: Graphic Lens Image -->
        <div class="relative flex justify-center z-10">
            <div class="relative w-full aspect-square max-w-md bg-[#edeeef] rounded-3xl overflow-hidden shadow-2xl border border-gray-150">
                <img class="w-full h-full object-cover select-none" loading="lazy" width="450" height="450" alt="Camera de supraveghere profesionala Optera Vision" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRyfzgrIpN8-vc51CjEdNqxx5j0gYrBqOR1AjSmPXwO8_wBwIfqjZxNmSSSocvXKhgU5px02rKEunWcyqH4eg4kZ21w_pKu7u36Gbmh9fjVB1FGvuq4CWbPFQsSzyvInZBQPgsQn6P7qTjMHk274-ayfkli7RBSZKzRS7zZhD1HKwJBrydopQdxCJCPPIrPXxeo9uFVTXLYXyMlnnJMrjQR3SRHkZgkLjKGf-freWyJ5Q6-0CBzrb7QzUkEWKkho8yGPFtYQkm5UVO">
                <div class="absolute inset-0 bg-gradient-to-t from-[#022717]/40 to-transparent"></div>
                
                <!-- Dynamic active system badge overlay -->
                <div class="absolute bottom-6 left-6 right-6 p-4 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-[#022717]">
                            <svg class="w-5 h-5 text-emerald-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] font-extrabold text-[#022717] tracking-widest uppercase">SISTEM SUPRAVEGHERE ACTIV</p>
                            <p class="text-[9px] text-[#545f73] font-bold">Protecție HD în timp real 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
