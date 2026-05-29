@extends('layouts.admin')

@section('title', 'SEO Manager Cockpit')
@section('section_title', 'Optimizare Motoare Căutare')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'global', seoTitle: '', seoDesc: '', seoSlug: '', seoUrl: '' }">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs Navigation Menu -->
    <div class="bg-white border border-slate-100 rounded-3xl p-2 shadow-sm flex flex-wrap gap-1">
        <button @click="activeTab = 'global'; seoTitle = '{{ addslashes($globalSeo['seo.default_title']) }}'; seoDesc = '{{ addslashes($globalSeo['seo.default_description']) }}'; seoSlug = ''; seoUrl = '{{ url('/') }}'" :class="activeTab === 'global' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-550 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            🌍 Setări SEO Globale
        </button>
        <button @click="activeTab = 'services'; seoTitle = ''; seoDesc = ''; seoSlug = ''; seoUrl = ''" :class="activeTab === 'services' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-555 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            🛠️ Meta Servicii Supraveghere
        </button>
        <button @click="activeTab = 'projects'; seoTitle = ''; seoDesc = ''; seoSlug = ''; seoUrl = ''" :class="activeTab === 'projects' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-555 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            📂 Meta Portofoliu Proiecte
        </button>
        <button @click="activeTab = 'pages'; seoTitle = ''; seoDesc = ''; seoSlug = ''; seoUrl = ''" :class="activeTab === 'pages' ? 'bg-emerald-850 text-white shadow-md' : 'text-slate-555 hover:bg-slate-50'" class="h-10 px-5 text-xs font-bold rounded-2xl transition-all focus:outline-none">
            📄 Meta Pagini CMS
        </button>
    </div>

    <!-- SEO Interactive Cockpit Workspace -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8 items-start">
        
        <!-- Left 2 Cols: SEO Forms Workspace -->
        <div class="xl:col-span-2 space-y-6">

            <!-- TAB 1: GLOBAL SEO SETTINGS -->
            <div x-show="activeTab === 'global'" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6 animate-fade-in">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Configurări SEO Globale Implicit</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Valori folosite când entitățile publice nu au titluri sau descrieri SEO definite</span>
                </div>

                @if(!auth()->user()->isAdmin())
                    <div class="p-4 bg-rose-50/50 border border-rose-100 text-rose-800 text-xs font-semibold rounded-2xl">
                        ⚠️ Rol neautorizat. Doar administratorii pot modifica setările de SEO globale ale site-ului.
                    </div>
                @else
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="group" value="system">

                        <div class="flex flex-col gap-1.5">
                            <label for="default_title" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Default Meta Title</label>
                            <input type="text" name="seo.default_title" id="default_title" value="{{ $globalSeo['seo.default_title'] }}" x-init="seoTitle = '{{ addslashes($globalSeo['seo.default_title']) }}'; seoUrl = '{{ url('/') }}'" @input="seoTitle = $event.target.value" class="h-10 px-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all" required>
                            <div class="flex items-center justify-between text-[8px] font-bold text-slate-450 mt-1">
                                <span>Titlu optim: 50-60 caractere</span>
                                <span :class="seoTitle.length > 60 ? 'text-red-600' : 'text-emerald-700'"><span x-text="seoTitle.length"></span> / 60</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="default_description" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Default Meta Description</label>
                            <textarea name="seo.default_description" id="default_description" rows="3" @input="seoDesc = $event.target.value" class="w-full p-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold leading-relaxed" required>{{ $globalSeo['seo.default_description'] }}</textarea>
                            <div class="flex items-center justify-between text-[8px] font-bold text-slate-455 mt-1" x-init="seoDesc = '{{ addslashes($globalSeo['seo.default_description']) }}'">
                                <span>Descriere optimă: 150-160 caractere</span>
                                <span :class="seoDesc.length > 160 ? 'text-amber-600' : 'text-emerald-700'"><span x-text="seoDesc.length"></span> / 160</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="robots_directives" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Robots.txt Directives Template</label>
                            <textarea name="system.robots_directives" id="robots_directives" rows="4" class="w-full p-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none leading-relaxed">{{ $globalSeo['system.robots_directives'] }}</textarea>
                        </div>

                        <div class="flex justify-end pt-3">
                            <button type="submit" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                                💾 Salvează SEO Implicit
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- TAB 2: SERVICES SEO OVERRIDES -->
            <div x-show="activeTab === 'services'" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6 animate-fade-in">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Meta Taguri SEO pentru Servicii</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Personalizează indexarea și snippet-ul Google pentru fiecare serviciu public</span>
                </div>

                <div class="space-y-4">
                    @forelse($services as $service)
                        <div class="border border-slate-100 rounded-2xl p-4 space-y-4 hover:border-slate-200 transition-colors" x-data="{ title: '{{ addslashes($service->meta_title ?: $service->title) }}', desc: '{{ addslashes($service->meta_description) }}', noindex: {{ $service->noindex ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                                <strong class="text-xs font-bold text-slate-800">🛠️ {{ $service->title }}</strong>
                                <button type="button" @click="seoTitle = title; seoDesc = desc; seoSlug = '{{ $service->slug }}'; seoUrl = '{{ route('services.show', $service->slug) }}'" class="text-[9px] font-bold text-emerald-800 hover:underline">
                                    👁️ Încarcă în Previzualizare Google
                                </button>
                            </div>

                            <form action="{{ route('admin.seo.entity.update', ['type' => 'service', 'id' => $service->id]) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Title Override</label>
                                        <input type="text" name="meta_title" x-model="title" class="h-9 px-3 text-[11px] bg-slate-50 border border-slate-200 rounded-xl font-semibold">
                                    </div>
                                    <div class="flex items-center h-9 pt-4">
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-[10px] font-bold text-slate-650">
                                            <input type="checkbox" name="noindex" value="1" x-model="noindex" class="w-3.5 h-3.5 text-emerald-800 rounded border-slate-200">
                                            Exclude de pe Google (noindex)
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Description Override</label>
                                    <textarea name="meta_description" x-model="desc" rows="2" class="w-full p-2.5 text-[11px] bg-slate-50 border border-slate-200 rounded-xl leading-relaxed"></textarea>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="px-4 h-8 text-[10px] font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg shadow transition-colors">
                                        💾 Salvează SEO Serviciu
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-450 font-medium">Nu există servicii active publicate în bază.</div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 3: PROJECTS SEO OVERRIDES -->
            <div x-show="activeTab === 'projects'" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6 animate-fade-in">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Meta Taguri SEO pentru Lucrări Portofoliu</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Personalizează indexarea Google pentru lucrările finalizate publicate</span>
                </div>

                <div class="space-y-4">
                    @forelse($projects as $project)
                        <div class="border border-slate-100 rounded-2xl p-4 space-y-4 hover:border-slate-200 transition-colors" x-data="{ title: '{{ addslashes($project->meta_title ?: $project->title) }}', desc: '{{ addslashes($project->meta_description) }}', noindex: {{ $project->noindex ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                                <strong class="text-xs font-bold text-slate-800">📂 {{ $project->title }}</strong>
                                <button type="button" @click="seoTitle = title; seoDesc = desc; seoSlug = '{{ $project->slug }}'; seoUrl = '{{ route('projects.show', $project->slug) }}'" class="text-[9px] font-bold text-emerald-800 hover:underline">
                                    👁️ Încarcă în Previzualizare Google
                                </button>
                            </div>

                            <form action="{{ route('admin.seo.entity.update', ['type' => 'project', 'id' => $project->id]) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Title Override</label>
                                        <input type="text" name="meta_title" x-model="title" class="h-9 px-3 text-[11px] bg-slate-50 border border-slate-200 rounded-xl font-semibold">
                                    </div>
                                    <div class="flex items-center h-9 pt-4">
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-[10px] font-bold text-slate-650">
                                            <input type="checkbox" name="noindex" value="1" x-model="noindex" class="w-3.5 h-3.5 text-emerald-800 rounded border-slate-200">
                                            Exclude de pe Google (noindex)
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Description Override</label>
                                    <textarea name="meta_description" x-model="desc" rows="2" class="w-full p-2.5 text-[11px] bg-slate-50 border border-slate-200 rounded-xl leading-relaxed"></textarea>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="px-4 h-8 text-[10px] font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg shadow transition-colors">
                                        💾 Salvează SEO Lucrare
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-450 font-medium">Nu există proiecte înregistrate în portofoliu.</div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 4: PAGES SEO OVERRIDES -->
            <div x-show="activeTab === 'pages'" class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6 animate-fade-in">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Meta Taguri SEO pentru Pagini CMS</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Personalizează indexarea paginilor de legi sau SEO local landing pages</span>
                </div>

                <div class="space-y-4">
                    @forelse($pages as $p)
                        <div class="border border-slate-100 rounded-2xl p-4 space-y-4 hover:border-slate-200 transition-colors" x-data="{ title: '{{ addslashes($p->meta_title ?: $p->title) }}', desc: '{{ addslashes($p->meta_description) }}', noindex: {{ $p->noindex ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                                <div class="flex items-center gap-2">
                                    <strong class="text-xs font-bold text-slate-800">📄 {{ $p->title }}</strong>
                                    <span class="text-[8px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-slate-50 border border-slate-100">{{ $p->type }}</span>
                                </div>
                                <button type="button" @click="seoTitle = title; seoDesc = desc; seoSlug = '{{ $p->slug }}'; seoUrl = '{{ route('pages.show', $p->slug) }}'" class="text-[9px] font-bold text-emerald-800 hover:underline">
                                    👁️ Încarcă în Previzualizare Google
                                </button>
                            </div>

                            <form action="{{ route('admin.seo.entity.update', ['type' => 'page', 'id' => $p->id]) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1">
                                        <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Title Override</label>
                                        <input type="text" name="meta_title" x-model="title" class="h-9 px-3 text-[11px] bg-slate-50 border border-slate-200 rounded-xl font-semibold">
                                    </div>
                                    <div class="flex items-center h-9 pt-4">
                                        <label class="inline-flex items-center gap-2 cursor-pointer text-[10px] font-bold text-slate-650">
                                            <input type="checkbox" name="noindex" value="1" x-model="noindex" class="w-3.5 h-3.5 text-emerald-800 rounded border-slate-200">
                                            Exclude de pe Google (noindex)
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[8px] font-bold text-slate-400 uppercase">Meta Description Override</label>
                                    <textarea name="meta_description" x-model="desc" rows="2" class="w-full p-2.5 text-[11px] bg-slate-50 border border-slate-200 rounded-xl leading-relaxed"></textarea>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="px-4 h-8 text-[10px] font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg shadow transition-colors">
                                        💾 Salvează SEO Pagină
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-450 font-medium">Nu există pagini CMS înregistrate.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Live Google Snippet Search Preview Card -->
        <div class="space-y-6 lg:col-span-1 xl:sticky xl:top-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Previzualizare Google Search</h4>
                    <span class="text-[8px] text-slate-400 mt-1 block font-semibold">Simulare vizuală live a snippet-ului în rezultatele căutării</span>
                </div>

                <!-- Google Snippet Card -->
                <div class="p-5 border border-slate-150/40 rounded-2xl shadow-sm bg-white space-y-2 max-w-sm">
                    <!-- URL segment -->
                    <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold truncate leading-none">
                        <span class="w-3.5 h-3.5 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-[7px]">🌐</span>
                        <span class="truncate" x-text="seoUrl ? seoUrl : 'https://opteravision.ro'"></span>
                    </div>

                    <!-- Title link segment -->
                    <h3 class="text-sm font-bold text-[#1a0dab] hover:underline cursor-pointer leading-tight break-words pt-1" x-text="seoTitle ? seoTitle : 'Nume Titlu SEO Implicit - Optera'"></h3>

                    <!-- Description segment -->
                    <p class="text-[11px] text-[#4d5156] font-medium leading-relaxed break-words" x-text="seoDesc ? seoDesc : 'Descriere implicită. Completează câmpurile din stânga sau apasă pe încărcare în previzualizare pentru a vedea randarea completă...'"></p>
                </div>

                <div class="p-4 bg-emerald-50/50 border border-emerald-100/50 rounded-2xl space-y-2">
                    <h5 class="text-[9px] font-extrabold text-emerald-850 uppercase tracking-wider block">Bune Practici SEO</h5>
                    <ul class="text-[9px] text-slate-500 font-semibold space-y-1.5 list-disc pl-4 leading-relaxed">
                        <li>Include cuvinte cheie principale în primele <strong>60 de caractere</strong> ale titlului.</li>
                        <li>Limitați descrierea la maxim <strong>160 de caractere</strong> pentru a evita trunchierea.</li>
                        <li>Paginile duplicat de local SEO trebuie să aibă titluri specifice (ex. "la Câmpulung Moldovenesc").</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
