@extends('layouts.public')

@section('title', 'Portofoliu Lucrări Supraveghere | Optera Vision')
@section('meta_description', 'Explorați portofoliul de proiecte finalizate cu succes de Optera Vision în Bucovina. Instalări camere securitate rezidențiale și comerciale în Câmpulung Moldovenesc.')

@section('content')
<main class="py-20 bg-slate-50/50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 w-full text-left">
        <!-- Header -->
        <div class="max-w-2xl mb-16 space-y-4">
            <span class="inline-block py-1.5 px-3 bg-[#1a3d2b] text-[#a9d0b6] rounded-full font-bold text-[9px] tracking-wider uppercase">
                PROIECTE REALE
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-[#022717] tracking-tight leading-tight">
                Portofoliu Lucrări Finalizate
            </h1>
            <p class="text-[#545f73] text-sm md:text-base leading-relaxed">
                Fiecare instalare realizată de echipa noastră reprezintă un angajament față de calitate și estetică. Explorați proiectele noastre de monitorizare video pentru clienți din Câmpulung Moldovenesc și zonele limitrofe.
            </p>
        </div>

        <!-- Projects Grid -->
        @if($projects->isEmpty())
            <div class="bg-white border border-gray-150 p-16 rounded-3xl text-center shadow-sm">
                <span class="text-3xl">📂</span>
                <p class="text-sm text-slate-500 font-semibold mt-4">Nu s-au găsit proiecte în portofoliu momentan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col justify-between">
                        <div>
                            <!-- Aspect ratio 16:9 for responsive images with width/height attrs and lazy-loading -->
                            <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                                @if($project->featured_image)
                                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" width="400" height="225" alt="{{ $project->title }}" src="{{ asset('storage/' . $project->featured_image) }}">
                                @else
                                    <!-- Elegant vector placeholder for project image -->
                                    <div class="w-full h-full flex items-center justify-center text-slate-350 bg-slate-50">
                                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Category & Locality tags overlay -->
                                <div class="absolute top-4 left-4 flex gap-2">
                                    <span class="px-2.5 py-1 bg-white/95 backdrop-blur-md rounded-md font-bold text-[8px] text-[#022717] tracking-wider uppercase shadow-sm">
                                        {{ $project->category ?? 'Supraveghere' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 space-y-3">
                                <div class="flex items-center gap-1.5 text-[10px] text-slate-450 font-bold leading-none uppercase">
                                    <span>📍</span>
                                    <span>{{ $project->locality ?? 'Bucovina' }}</span>
                                </div>
                                <h3 class="text-md font-extrabold text-[#022717] group-hover:text-emerald-950 transition-colors">
                                    {{ $project->title }}
                                </h3>
                                <p class="text-xs text-[#545f73] leading-relaxed line-clamp-3">
                                    {{ $project->short_description }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ url('/proiecte/' . $project->slug) }}" class="inline-flex items-center justify-center w-full h-10 border border-slate-200 hover:border-[#022717] text-[#022717] hover:bg-slate-50 font-bold text-xs rounded-xl transition-all uppercase">
                                Detalii Proiect
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Styled Pagination links wrapper -->
            <div class="mt-16 flex items-center justify-center">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
