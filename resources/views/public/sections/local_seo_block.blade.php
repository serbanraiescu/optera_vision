@php
    $seoBlock = $blocks->where('block_key', 'seo_text_block')->first();
    $seoContent = $seoBlock ? $seoBlock->content : [];
@endphp

<section class="py-24 px-6 md:px-16 bg-white border-t border-slate-100">
    <div class="max-w-[1280px] mx-auto w-full">
        <div class="bg-gradient-to-br from-[#f8f9fa] to-white p-8 md:p-16 rounded-3xl border border-gray-150 text-left grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <!-- Left Header -->
            <div class="lg:col-span-5 space-y-4">
                <span class="font-bold text-xs tracking-widest text-[#022717] uppercase">ACOPERIRE REGIONALĂ</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-[#022717] tracking-tight leading-tight">
                    {{ $seoContent['title'] ?? 'Securitate garantată în Câmpulung Moldovenesc și toată Bucovina' }}
                </h3>
            </div>

            <!-- Right Body -->
            <div class="lg:col-span-7 space-y-6">
                <p class="font-sans text-xs md:text-sm text-[#545f73] leading-relaxed">
                    {{ $seoContent['body'] ?? 'Fie că ai nevoie de camere de supraveghere video pentru o locuință privată în Câmpulung Moldovenesc, sau de o infrastructură complexă de monitorizare pentru un spațiu comercial din județul Suceava, Optera Vision îți oferă echipamente DVR/NVR moderne și servicii complete de service, mentenanță și cablare structurată.' }}
                </p>
                <div class="pt-2">
                    <a href="{{ url('/despre-noi') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#022717] hover:underline uppercase tracking-wider">
                        {{ $seoContent['link_text'] ?? 'Află mai multe despre noi' }}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
