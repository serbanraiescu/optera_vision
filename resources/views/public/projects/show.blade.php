@extends('layouts.public')

@section('title', ($project->meta_title ?: $project->title . ' | Optera Vision Portofoliu'))
@section('meta_description', ($project->meta_description ?: $project->short_description))

@section('content')
<main class="py-20 bg-slate-50/50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-16 w-full text-left">
        <!-- Breadcrumb back link -->
        <div class="mb-8">
            <a href="{{ url('/proiecte') }}" class="text-xs font-bold text-[#545f73] hover:text-[#022717] transition-colors flex items-center gap-1">
                &larr; Înapoi la portofoliu
            </a>
        </div>

        <!-- Project layout grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Column: Description & Image Gallery (8/12 cols) -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Core Description Card -->
                <div class="bg-white p-8 md:p-12 rounded-3xl border border-gray-150 shadow-sm shadow-slate-100/10 space-y-6">
                    <div class="space-y-3">
                        <div class="flex items-center gap-1.5 text-[10px] text-emerald-800 font-extrabold uppercase tracking-wider">
                            <span>📍 {{ $project->locality ?? 'Bucovina' }}</span>
                            <span>&bull;</span>
                            <span>🏷️ {{ $project->category ?? 'Supraveghere Video' }}</span>
                        </div>
                        <h1 class="text-2xl md:text-4xl font-extrabold text-[#022717] tracking-tight">
                            {{ $project->title }}
                        </h1>
                    </div>

                    <div class="prose prose-emerald max-w-none text-xs md:text-sm text-slate-650 leading-relaxed border-t border-slate-100 pt-6">
                        {!! nl2br(e($project->full_description)) !!}
                    </div>
                </div>

                <!-- Gallery Grid -->
                @if($project->images->isNotEmpty())
                    <div class="space-y-6">
                        <h3 class="text-sm font-extrabold text-[#022717] tracking-wider uppercase flex items-center gap-2">
                            <span>📸</span> Galerie Imagini Proiect
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($project->images as $img)
                                <div class="bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm aspect-video relative group">
                                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" width="600" height="338" alt="{{ $project->title }} - Galerie" src="{{ asset('storage/' . $img->image_path) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Specs Sidebar & Related Projects (4/12 cols) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Project Specifications Info -->
                <div class="bg-white border border-gray-150 p-6 md:p-8 rounded-3xl shadow-sm shadow-slate-100/10 text-left space-y-6">
                    <h3 class="text-sm font-extrabold text-[#022717] tracking-wider uppercase border-b border-slate-100 pb-3 leading-none">
                        Fisă Tehnică Lucrare
                    </h3>
                    <ul class="space-y-4 text-xs font-semibold text-slate-600">
                        <li class="flex items-center justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Locație:</span>
                            <span class="text-[#022717] font-bold">{{ $project->locality ?? 'Câmpulung Moldovenesc' }}</span>
                        </li>
                        <li class="flex items-center justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Categorie:</span>
                            <span class="text-[#022717] font-bold">{{ $project->category ?? 'Supraveghere' }}</span>
                        </li>
                        <li class="flex items-center justify-between border-b border-slate-50 pb-2">
                            <span class="text-slate-400">Status Lucrare:</span>
                            <span class="text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase">Finalizat</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-slate-400">Timp execuție:</span>
                            <span class="text-[#022717] font-bold">1-2 Zile</span>
                        </li>
                    </ul>
                </div>

                <!-- Related Projects Recommendations -->
                @if($relatedProjects->isNotEmpty())
                    <div class="space-y-6 text-left">
                        <h3 class="text-xs font-extrabold text-[#022717] tracking-wider uppercase border-b border-slate-150/40 pb-3 leading-none">
                            Alte Proiecte Similare
                        </h3>
                        <div class="space-y-4">
                            @foreach($relatedProjects as $relProj)
                                <a href="{{ url('/proiecte/' . $relProj->slug) }}" class="flex items-center gap-4 bg-white border border-gray-150 p-4 rounded-2xl hover:shadow-md hover:border-emerald-700/25 transition-all group">
                                    <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                        @if($relProj->featured_image)
                                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" width="80" height="80" alt="{{ $relProj->title }}" src="{{ asset('storage/' . $relProj->featured_image) }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                📷
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-0.5 min-w-0">
                                        <span class="text-[9px] font-extrabold text-slate-400 uppercase">📍 {{ $relProj->locality }}</span>
                                        <h4 class="text-xs font-bold text-[#022717] truncate group-hover:text-emerald-950 transition-colors">{{ $relProj->title }}</h4>
                                        <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mt-1 inline-flex items-center gap-0.5">
                                            Vezi proiect
                                            <span>&rarr;</span>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
