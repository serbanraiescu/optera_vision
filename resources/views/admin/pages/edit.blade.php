@extends('layouts.admin')

@section('title', isset($page) ? 'Editează Pagină: ' . $page->title : 'Adaugă Pagină Nouă')
@section('section_title', isset($page) ? 'Editează Pagină CMS' : 'Adaugă Pagină CMS')

@section('head')
<!-- TinyMCE CDN Rich Text HTML Editor -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#content_editor',
            plugins: 'lists link image table code media wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image code',
            height: 450,
            branding: false,
            promotion: false,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); // Sync content instantly to textarea for Form submission
                });
            }
        });
    });
</script>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header bar -->
    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
        <a href="{{ route('admin.pages.index') }}" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/40 text-slate-550 hover:bg-slate-100 flex items-center justify-center transition-colors text-xs" title="Înapoi la Liste Pagini">
            ←
        </a>
        <div class="flex flex-col">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">
                {{ isset($page) ? 'Editare: ' . $page->title : 'Creare Pagină CMS' }}
            </h3>
            <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">
                {{ isset($page) ? 'Actualizează conținutul și SEO-ul paginii #' . $page->id : 'Adaugă pagină nouă în sistem' }}
            </span>
        </div>
    </div>

    <!-- Edit/Create Form -->
    <form action="{{ isset($page) ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8 items-start">
        @csrf
        @if(isset($page))
            @method('PUT')
        @endif

        <!-- Left 2 Cols: Main Text Editor Workspace -->
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
                <!-- Page Title -->
                <div class="flex flex-col gap-1.5">
                    <label for="title" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Titlu Pagină</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title ?? '') }}" class="h-10 px-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold" required>
                </div>

                <!-- Rich text Editor area -->
                <div class="flex flex-col gap-1.5" x-data="{ type: '{{ old('type', $page->type ?? 'legal') }}' }">
                    <div class="flex items-center justify-between mb-1">
                        <label for="content_editor" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Conținut Pagină (Rich HTML)</label>
                        
                        <!-- Dynamic Placeholders help segment -->
                        <div x-show="type === 'local_seo'" class="text-[8px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded">
                            Suportă placeholders: <code>[localitate]</code>, <code>[judet]</code>
                        </div>
                    </div>
                    <textarea name="content" id="content_editor" class="w-full">{{ old('content', $page->content ?? '') }}</textarea>
                </div>
            </div>

            <!-- Page SEO Parameters Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Configurare Meta Tags SEO</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="meta_title" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Meta Title Override</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="noindex" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Indexare Căutare (Robots)</label>
                        <div class="flex items-center h-10">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="checkbox" name="noindex" value="1" {{ old('noindex', $page->noindex ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded text-emerald-800 bg-slate-100 border-slate-200 focus:ring-emerald-800">
                                Ascunde pagina de pe Google (noindex)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="meta_description" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Meta Description Override</label>
                    <textarea name="meta_description" id="meta_description" rows="3" class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold leading-relaxed">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Metadata & Layout Attributes Panel -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Atribute Pagină</h4>
                </div>

                <!-- Page Type dropdown -->
                <div class="flex flex-col gap-1.5" x-data="{ type: '{{ old('type', $page->type ?? 'legal') }}' }">
                    <label for="type_select" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Tip Pagină</label>
                    <select name="type" id="type_select" x-model="type" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                        <option value="legal">Legal (GDPR, Termeni, ANPC)</option>
                        <option value="local_seo">SEO Local (Landing Pages)</option>
                        <option value="custom">Personalizată / Standard</option>
                    </select>

                    <!-- Master/Child local relations (Only visible if type === 'local_seo') -->
                    <div x-show="type === 'local_seo'" class="mt-4 flex flex-col gap-1.5 p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl animate-fade-in">
                        <label for="parent_select" class="text-[8px] font-extrabold text-emerald-800 uppercase tracking-wider">Relație Pagina Master</label>
                        <select name="parent_id" id="parent_select" class="h-9 px-3 text-[11px] bg-white border border-emerald-250 rounded-xl focus:outline-none font-semibold">
                            <option value="">Niciuna (Aceasta este Pagină Master)</option>
                            @foreach($masterTemplates as $master)
                                <option value="{{ $master->id }}" {{ old('parent_id', $page->parent_id ?? '') == $master->id ? 'selected' : '' }}>
                                    {{ $master->title }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[8px] text-emerald-850 leading-relaxed mt-1 block">
                            *Creează relații parent-child între landers pentru a simplifica gestiunea și replicarea conținutului.
                        </span>
                    </div>
                </div>

                <!-- Slug manual locks -->
                <div class="flex flex-col gap-1.5">
                    <label for="slug" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">URL Link (Slug)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="Lăsați gol pentru auto-generare..." class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                    @if(isset($page))
                        <span class="text-[8px] text-slate-400 font-semibold leading-none mt-1">
                            Link direct public: <a href="{{ url($page->slug) }}" target="_blank" class="text-emerald-800 hover:underline">/{{ $page->slug }} ↗</a>
                        </span>
                    @endif
                </div>

                <!-- Status select -->
                <div class="flex flex-col gap-1.5">
                    <label for="status" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Stare Publicare</label>
                    <select name="status" id="status" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                        <option value="draft" {{ old('status', $page->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft (Ciornă)</option>
                        <option value="published" {{ old('status', $page->status ?? '') === 'published' ? 'selected' : '' }}>Publicată (Live)</option>
                        <option value="archived" {{ old('status', $page->status ?? '') === 'archived' ? 'selected' : '' }}>Arhivată</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-50 flex flex-col gap-2">
                    <button type="submit" class="w-full h-10 inline-flex items-center justify-center text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                        💾 Salvează Pagina CMS
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="w-full h-10 inline-flex items-center justify-center text-xs font-bold text-slate-550 bg-slate-50 border border-slate-200 hover:bg-slate-100 rounded-xl transition-all focus:outline-none">
                        Renunță
                    </a>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
