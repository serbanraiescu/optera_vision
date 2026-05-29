@extends('layouts.public')

@section('title', 'Contactați-ne | Optera Vision Câmpulung Moldovenesc')
@section('meta_description', 'Contactați echipa Optera Vision pentru instalare, configurare sau service sisteme de supraveghere video în Câmpulung Moldovenesc, Suceava și Bucovina.')

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white py-16 px-6 md:px-16">
    <div class="max-w-[1280px] mx-auto w-full">
        <!-- Title Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span class="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[9px] tracking-wider uppercase">
                Contact & Asistență
            </span>
            <h1 class="font-extrabold text-4xl md:text-5xl text-[#022717] tracking-tight">
                Să discutăm despre securitatea ta
            </h1>
            <p class="font-sans text-sm md:text-md text-[#545f73] leading-relaxed">
                Fie că dorești un sistem nou complet sau service pentru un sistem existent, echipa noastră din Câmpulung Moldovenesc este gata să te ajute.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Left Column: Contact info & metadata -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Info cards grid -->
                <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm space-y-8">
                    <h3 class="text-lg font-bold text-[#022717] border-b border-slate-100 pb-4 flex items-center gap-2">
                        <span>ℹ️</span> Date de Contact
                    </h3>

                    <!-- Phone Number -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#022717] shrink-0">
                            📞
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-[#545f73] uppercase tracking-wider">Telefon asistență</p>
                            <a href="tel:{{ str_replace(' ', '', setting('company.phone')) }}" class="font-bold text-sm text-[#022717] hover:underline hover:text-emerald-950 transition-colors">
                                {{ setting('company.phone') }}
                            </a>
                        </div>
                    </div>

                    <!-- Clickable WhatsApp -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#25d366]/10 border border-[#25d366]/20 flex items-center justify-center text-[#25d366] shrink-0">
                            <svg class="w-5 h-5 text-[#128c7e]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.517 2.266 2.27 3.51 5.282 3.51 8.485-.006 6.66-5.343 11.997-11.958 11.997-2.005-.003-3.973-.505-5.724-1.46L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.003-2.637-1.03-5.114-2.908-6.993C16.65 1.87 14.17 .837 11.53 .837c-5.44 0-9.867 4.424-9.871 9.87-.001 1.701.455 3.361 1.32 4.816l-.995 3.636 3.737-.98 1.335.795zm11.303-7.794c-.3-.15-1.772-.875-2.046-.975-.276-.1-.476-.15-.676.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.797-1.49-1.78-1.666-2.08-.175-.3-.02-.463.13-.612.135-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.676-1.625-.926-2.225-.244-.588-.492-.508-.676-.518-.174-.01-.374-.012-.574-.012-.2 0-.526.075-.802.375-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.11 3.224 5.116 4.525.715.31 1.273.495 1.707.633.718.228 1.37.195 1.887.118.577-.087 1.772-.725 2.022-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.275-.275-.575-.425z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-[#545f73] uppercase tracking-wider">WhatsApp rapid</p>
                            <a href="https://wa.me/{{ str_replace('+', '', str_replace(' ', '', setting('company.whatsapp'))) }}" target="_blank" rel="noopener" class="font-bold text-sm text-[#128c7e] hover:underline hover:text-emerald-950 transition-colors flex items-center gap-1">
                                Scrie-ne direct &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#022717] shrink-0">
                            ✉️
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-[#545f73] uppercase tracking-wider">E-mail office</p>
                            <a href="mailto:{{ setting('company.email') }}" class="font-bold text-sm text-[#022717] hover:underline hover:text-emerald-950 transition-colors">
                                {{ setting('company.email') }}
                            </a>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-[#022717] shrink-0">
                            📍
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-[#545f73] uppercase tracking-wider">Adresă Sediu</p>
                            <p class="text-xs font-semibold text-[#022717] leading-relaxed">
                                {{ setting('company.address') }}
                            </p>
                        </div>
                    </div>

                    <!-- Opening Schedule -->
                    <div class="flex items-start gap-4 border-t border-slate-100 pt-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-center text-slate-500 shrink-0">
                            🕐
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Program funcționare</p>
                            <p class="text-xs font-semibold text-slate-700 leading-relaxed">
                                {{ setting('company.schedule') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Served Areas Card -->
                <div class="bg-[#1a3d2b]/5 border border-[#1a3d2b]/15 rounded-3xl p-8 space-y-4">
                    <h4 class="text-sm font-extrabold text-[#022717] uppercase tracking-widest flex items-center gap-2">
                        <span>🗺️</span> Zone Deservite Activ
                    </h4>
                    <p class="text-xs text-[#545f73] leading-relaxed">
                        Ne deplasăm gratuit pentru evaluări tehnice și măsurători la fața locului în:
                    </p>
                    <p class="text-xs font-bold text-[#022717] leading-relaxed bg-white/70 backdrop-blur-sm border border-emerald-900/10 p-4 rounded-2xl">
                        {{ setting('company.served_area', 'Câmpulung Moldovenesc, Suceava, Gura Humorului, Vatra Dornei, Rădăuți și zonele limitrofe din Bucovina.') }}
                    </p>
                </div>
            </div>

            <!-- Right Column: Interactive configurator & maps -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Quote configurator container -->
                <div class="bg-white border border-gray-150 rounded-3xl p-8 shadow-sm">
                    <x-public.quote-configurator />
                </div>

                <!-- Map block -->
                <div class="bg-white border border-gray-150 rounded-3xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-extrabold text-[#022717] uppercase tracking-wider">Harta Sediului Optera Vision</span>
                        <a href="https://maps.google.com/?q={{ urlencode(setting('company.address')) }}" target="_blank" rel="noopener" class="text-[10px] font-extrabold text-[#128c7e] hover:underline uppercase tracking-wider">&Icirc;n aplicație &rarr;</a>
                    </div>
                    <div class="w-full h-80 bg-slate-100 flex items-center justify-center">
                        @if(setting('company.map_iframe'))
                            {!! setting('company.map_iframe') !!}
                        @else
                            <!-- Beautiful dynamic Câmpulung Moldovenesc embed fallback -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d21535.12199616016!2d25.556272589550785!3d47.5310344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473663b65ef731db%3A0xc3c94d03e5c9cd8c!2zQ8OibXB1bHVuZyBNb2xkb3ZlbmVzYw!5e0!3m2!1sro!2sro!4v1716912345678!5m2!1sro!2sro" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
