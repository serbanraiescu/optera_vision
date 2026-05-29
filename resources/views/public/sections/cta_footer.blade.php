@php
    $ctaBlock = $blocks->where('block_key', 'cta_banner')->first();
    $ctaContent = $ctaBlock ? $ctaBlock->content : [];
@endphp

<section class="py-24 px-6 md:px-16 bg-[#f8f9fa] border-t border-slate-100">
    <div class="max-w-[1280px] mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#022717] mb-6">
            {{ $ctaContent['title'] ?? 'Pregătit pentru siguranță?' }}
        </h2>
        <p class="text-xs md:text-sm text-[#545f73] mb-12 max-w-xl mx-auto leading-relaxed font-medium">
            {{ $ctaContent['subtitle'] ?? 'Cere o ofertă personalizată sau sună-ne direct pentru o evaluare gratuită la fața locului în Câmpulung Moldovenesc și localitățile învecinate.' }}
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 max-w-md mx-auto">
            <a href="https://wa.me/{{ str_replace('+', '', str_replace(' ', '', setting('company.whatsapp'))) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-3 bg-[#25D366] text-white px-8 py-4 rounded-xl font-bold hover:shadow-lg hover:shadow-green-500/10 hover:opacity-95 transition-all text-xs tracking-wider active:scale-95 uppercase">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.517 2.266 2.27 3.51 5.282 3.51 8.485-.006 6.66-5.343 11.997-11.958 11.997-2.005-.003-3.973-.505-5.724-1.46L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.003-2.637-1.03-5.114-2.908-6.993C16.65 1.87 14.17 .837 11.53 .837c-5.44 0-9.867 4.424-9.871 9.87-.001 1.701.455 3.361 1.32 4.816l-.995 3.636 3.737-.98 1.335.795zm11.303-7.794c-.3-.15-1.772-.875-2.046-.975-.276-.1-.476-.15-.676.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.797-1.49-1.78-1.666-2.08-.175-.3-.02-.463.13-.612.135-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.676-1.625-.926-2.225-.244-.588-.492-.508-.676-.518-.174-.01-.374-.012-.574-.012-.2 0-.526.075-.802.375-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.11 3.224 5.116 4.525.715.31 1.273.495 1.707.633.718.228 1.37.195 1.887.118.577-.087 1.772-.725 2.022-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.275-.275-.575-.425z" />
                </svg>
                Contact WhatsApp
            </a>
            <a href="mailto:{{ setting('company.email') }}" class="inline-flex items-center justify-center gap-3 bg-[#022717] text-white px-8 py-4 rounded-xl font-bold hover:shadow-lg hover:shadow-emerald-900/10 hover:bg-[#1a3d2b] transition-all text-xs tracking-wider active:scale-95 uppercase">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Trimite Email
            </a>
        </div>
    </div>
</section>
