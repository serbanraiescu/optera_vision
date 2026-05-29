@extends('layouts.public')

@section('title', 'Cere Ofertă Personalizată | Optera Vision')
@section('meta_description', 'Completați configuratorul rapid de supraveghere video Optera Vision pentru a primi o ofertă complet personalizată și gratuită în maximum 24h.')

@section('content')
<main class="py-16 bg-slate-50/50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left Side: Context & Visuals (5/12 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-8 text-left">
            <div class="space-y-4">
                <span class="font-bold text-xs tracking-widest text-[#022717] uppercase">Securitate Inteligentă</span>
                <h1 class="text-3xl md:text-5xl font-extrabold text-[#022717] tracking-tight leading-tight">
                    Configurează-ți sistemul de protecție.
                </h1>
                <p class="font-sans text-xs md:text-sm text-[#545f73] leading-relaxed">
                    Completați configuratorul rapid de mai jos pentru a primi o ofertă detaliată și complet personalizată. Inginerii noștri din Câmpulung Moldovenesc vor analiza spațiul dvs. și vor proiecta gratuit cea mai eficientă schemă tehnică de supraveghere.
                </p>
            </div>

            <!-- Premium Camera Image overlay -->
            <div class="relative rounded-3xl overflow-hidden aspect-[4/3] shadow-md group bg-gray-50 border border-gray-150">
                <img alt="Camera de supraveghere moderna de exterior" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 select-none" loading="lazy" width="400" height="300" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCce1wCnTQqjS6tDVHtqdarxC4a0kRwNZDVJNlfnz76-Smyp68TIjdWocwpqDYZQxNHpKJbEUuUQ3Ps-_evMOWLgGFrKWdeeDe443eu6aec-6Y8A6b98j7-JhJA9rg8zLps1r0qmWNFHKYY75C0R168RR4f-U4u4Nh0NjoHENZswrgTcc20h9Y3ZKk8U3rTXdO7anPUs5QjLhOCxf2iMesXIMIeDlebtPxQzKzkfzDnxcmemOkG_j3JvZsQcVSDprRONsCuDzv3Yfq0">
                <div class="absolute inset-0 bg-gradient-to-t from-[#022717]/40 to-transparent"></div>
                
                <div class="absolute bottom-6 left-6 right-6 p-4 bg-white/90 backdrop-blur-md rounded-2xl border border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 text-[#022717] rounded-full">
                            <svg class="w-4.5 h-4.5 text-emerald-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-[#022717]">Tehnologie autorizată în Bucovina</h4>
                            <p class="text-[9px] text-[#545f73] font-bold">Proiectare gratuită de ingineri autorizați IGPR</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust parameters -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-5 bg-white rounded-2xl border border-gray-150 shadow-sm flex flex-col gap-1.5">
                    <span class="w-8 h-8 rounded-lg bg-[#022717] text-[#a9d0b6] flex items-center justify-center text-xs font-bold font-mono">24h</span>
                    <h4 class="font-bold text-xs text-[#022717]">Răspuns Rapid</h4>
                    <p class="text-[10px] text-[#545f73] font-medium leading-relaxed">Deviz și proiect tehnic gratuit în maximum 24h.</p>
                </div>

                <div class="p-5 bg-white rounded-2xl border border-gray-150 shadow-sm flex flex-col gap-1.5">
                    <span class="w-8 h-8 rounded-lg bg-[#022717] text-[#a9d0b6] flex items-center justify-center text-xs font-bold font-mono">Ro</span>
                    <h4 class="font-bold text-xs text-[#022717]">Suport Tehnic</h4>
                    <p class="text-[10px] text-[#545f73] font-medium leading-relaxed">Asistență directă oferită de tehnicieni locali.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Configurator Form Component (7/12 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-slate-100/80">
            <x-public.quote-configurator />
        </div>

    </div>
</main>
@endsection
