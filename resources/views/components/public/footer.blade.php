@php
    // Cache queries directly to prevent repeated DB requests on footer renders
    $footerLegalMenu = \Illuminate\Support\Facades\Cache::remember('footer_published_legal_pages_menu', 3600, function () {
        return \App\Models\Page::published()->legal()->get(['title', 'slug']);
    });
@endphp

<footer class="bg-slate-900 text-slate-400 border-t border-slate-800 py-16 pb-32 md:pb-16 mt-auto">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 w-full">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Branding column -->
            <div class="flex flex-col gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-800/10 flex items-center justify-center border border-emerald-800/20">
                        <svg class="w-4 h-4 text-[var(--color-accent)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-md font-bold tracking-tight text-white leading-none">{{ setting('site.name', 'OPTERA VISION') }}</span>
                </a>
                <p class="text-xs text-slate-500 leading-relaxed mt-2">
                    {{ setting('site.description', 'Sisteme de supraveghere video profesionale în județul Suceava. Siguranță garantată pentru casa sau afacerea ta.') }}
                </p>
                <p class="text-[10px] text-slate-650 leading-relaxed font-semibold mt-2">
                    CUI: {{ setting('company.cui') }} <br>
                    Reg. Com: {{ setting('company.reg_number') }}
                </p>
            </div>

            <!-- Navigation Links -->
            <div>
                <h4 class="text-xs font-bold text-white tracking-widest uppercase mb-6">Navigare</h4>
                <ul class="space-y-4 text-xs font-semibold">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Acasă</a></li>
                    <li><a href="{{ url('/servicii') }}" class="hover:text-white transition-colors">Servicii video</a></li>
                    <li><a href="{{ url('/proiecte') }}" class="hover:text-white transition-colors">Portofoliu lucrări</a></li>
                    <li><a href="{{ url('/solicita-oferta') }}" class="hover:text-white transition-colors">Cere preț gratuit</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white transition-colors">Pagina de contact</a></li>
                </ul>
            </div>

            <!-- Contact & Schedule -->
            <div>
                <h4 class="text-xs font-bold text-white tracking-widest uppercase mb-6">Contact &amp; Servicii</h4>
                <ul class="space-y-4 text-xs font-semibold leading-relaxed">
                    <li class="flex items-start gap-2.5">
                        <span class="text-[var(--color-accent)] mt-0.5">📍</span>
                        <span>{{ setting('company.address') }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-[var(--color-accent)]">📞</span>
                        <a href="tel:{{ str_replace(' ', '', setting('company.phone')) }}" class="hover:text-white transition-colors">{{ setting('company.phone') }}</a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-[var(--color-accent)]">✉️</span>
                        <a href="mailto:{{ setting('company.email') }}" class="hover:text-white transition-colors">{{ setting('company.email') }}</a>
                    </li>
                    <li class="flex items-start gap-2.5 border-t border-slate-800 pt-3 mt-3">
                        <span class="text-slate-500">🕐</span>
                        <div class="flex flex-col gap-0.5 text-slate-500">
                            <span class="text-[10px] uppercase font-extrabold tracking-wider text-slate-400">Program</span>
                            <span>{{ setting('company.schedule') }}</span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Dynamic Legal Links CMS -->
            <div>
                <h4 class="text-xs font-bold text-white tracking-widest uppercase mb-6">Link-uri Legale</h4>
                <ul class="space-y-4 text-xs font-semibold">
                    @foreach($footerLegalMenu as $legalPage)
                        <li>
                            <a href="{{ url('/' . $legalPage->slug) }}" class="hover:text-white transition-colors">
                                {{ $legalPage->title }}
                            </a>
                        </li>
                    @endforeach
                    <li class="flex flex-col gap-2 pt-2">
                        <a href="{{ setting('brand.anpc_link', 'https://anpc.ro/') }}" target="_blank" rel="nofollow noopener" class="inline-flex items-center h-8 bg-slate-800 hover:bg-slate-700 text-[10px] text-slate-350 font-bold px-3 rounded-lg border border-slate-700/50 shadow-sm transition-all w-24">ANPC</a>
                        <a href="{{ setting('brand.sol_link', 'https://ec.europa.eu/consumers/odr/') }}" target="_blank" rel="nofollow noopener" class="inline-flex items-center h-8 bg-slate-800 hover:bg-slate-700 text-[10px] text-slate-355 font-bold px-3 rounded-lg border border-slate-700/50 shadow-sm transition-all w-24">SOL</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Copyright bottom -->
        <div class="border-t border-slate-800/80 mt-16 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[11px] text-slate-500 font-medium">
                &copy; {{ date('Y') }} {{ setting('company.name', 'OPTERA VISION S.R.L.') }}. Toate drepturile rezervate.
            </span>
            <div class="flex flex-col md:flex-row items-center gap-4 text-[10px] text-slate-650 font-medium">
                <div class="flex items-center gap-1.5">
                    <span>Created by</span>
                    <a href="https://daser.ro" target="_blank" class="hover:underline text-slate-550 font-bold">Daser technologies</a>
                </div>
                <div class="h-3 w-px bg-slate-850 hidden md:block"></div>
                <a href="https://daser.ro" target="_blank" class="opacity-75 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('storage/daser-logo-light.png') }}" alt="Daser Technologies Logo" class="h-4 object-contain">
                </a>
            </div>
        </div>
    </div>
</footer>
