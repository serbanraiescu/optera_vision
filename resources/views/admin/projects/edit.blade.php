@extends('layouts.admin')

@section('title', isset($project) ? 'Editează Lucrare: ' . $project->title : 'Adaugă Lucrare Nouă')
@section('section_title', 'Administrare Portofoliu')

@section('content')
<div class="space-y-6" x-data="projectForm({{ isset($project) ? json_encode($project) : 'null' }}, {{ isset($project) ? json_encode($project->images->pluck('image_path')) : '[]' }})">

    @if($errors->any())
        <div class="p-4 bg-rose-50 text-rose-850 border border-rose-250/50 rounded-2xl text-xs font-semibold">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($project) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8 items-start">
        @csrf
        @if(isset($project))
            @method('PUT')
        @endif

        <!-- Left 2 Cols: Main Editor content -->
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-50 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Informații Lucrare</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Titlu lucrare, localitate, categorie și descrieri detaliate</span>
                </div>

                <!-- Title & Slug -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="title" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Titlu Lucrare</label>
                        <input type="text" name="title" id="title" x-model="title" @input="onTitleInput" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold" required>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="slug" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Slug / Link Personalizat</label>
                        <div class="relative">
                            <input type="text" name="slug" id="slug" x-model="slug" :readonly="slugLocked" :class="slugLocked ? 'bg-slate-100/70 text-slate-400 cursor-not-allowed' : 'bg-slate-50 focus:bg-white'" class="w-full h-10 pl-3 pr-10 text-xs border border-slate-200 rounded-xl focus:outline-none transition-all font-mono font-semibold">
                            <button type="button" @click="slugLocked = !slugLocked" class="absolute right-3 top-3 text-[10px]" title="Deblochează Link">
                                <span x-show="slugLocked">🔒</span>
                                <span x-show="!slugLocked">🔓</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Category & Locality -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="category" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Categorie</label>
                        <select name="category" id="category" x-model="category" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold cursor-pointer">
                            <option value="Rezidențial">🏡 Rezidențial</option>
                            <option value="Comercial">🏢 Comercial</option>
                            <option value="Industrial">🏭 Industrial</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="locality" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Localitate Lucrare</label>
                        <input type="text" name="locality" id="locality" x-model="locality" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold" placeholder="Ex: Câmpulung Moldovenesc, Suceava..." required>
                    </div>
                </div>

                <!-- Short Description -->
                <div class="flex flex-col gap-1.5">
                    <label for="short_description" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Descriere Scurtă (Rezumat listare)</label>
                    <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $project->short_description ?? '') }}" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold" placeholder="Rezumat scurt afișat în pagina de portofoliu. Max 250 caractere.">
                </div>

                <!-- Full Rich Description -->
                <div class="flex flex-col gap-1.5">
                    <label for="full_description" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Descriere Detaliată / Raport Tehnic (Editor HTML)</label>
                    <textarea name="full_description" id="full_description" class="hidden">{{ old('full_description', $project->full_description ?? '') }}</textarea>
                </div>
            </div>

            <!-- Dynamic Multi-Image Photo Gallery Management Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-50 pb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Galerie Foto Lucrare (Multi-Image)</h3>
                        <span class="text-[10px] text-slate-400 mt-2 font-medium block">Adaugă fotografii detaliate ale echipamentelor și montajului finalizat. Reordonează-le după plac.</span>
                    </div>
                    <button type="button" @click="openMediaSelector('gallery')" class="shrink-0 px-4 h-8 text-[10px] font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-sm">
                        🖼️ Adaugă din Mediatecă
                    </button>
                </div>

                <!-- Gallery list grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" x-show="galleryImages.length > 0">
                    <template x-for="(path, index) in galleryImages" :key="path">
                        <div class="border border-slate-150 rounded-2xl p-2.5 bg-slate-50/50 flex flex-col justify-between relative group hover:border-emerald-700/40 hover:bg-white transition-all shadow-sm">
                            <!-- Hidden inputs to submit gallery order -->
                            <input type="hidden" name="gallery_images[]" :value="path">
                            
                            <!-- Thumbnail with badge number -->
                            <div class="aspect-video bg-white border border-slate-150 rounded-xl overflow-hidden relative flex items-center justify-center">
                                <img :src="'/storage/' + path" class="max-w-full max-h-full object-cover">
                                <span class="absolute top-1 left-1 w-5 h-5 rounded-full bg-slate-900/80 text-white font-mono text-[9px] font-bold flex items-center justify-center" x-text="index + 1"></span>
                            </div>
                            
                            <!-- Path text & sorting controllers -->
                            <div class="mt-2.5 space-y-2">
                                <span class="text-[8px] font-mono text-slate-400 truncate block text-center" x-text="path.split('/').pop()"></span>
                                
                                <div class="flex items-center justify-between border-t border-slate-100 pt-2">
                                    <div class="flex items-center gap-1">
                                        <!-- Move Left/Up -->
                                        <button type="button" @click="moveLeft(index)" :disabled="index === 0" class="w-5 h-5 rounded border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-[8px] disabled:opacity-30 disabled:cursor-not-allowed" title="Mută la stânga">
                                            ◀️
                                        </button>
                                        <!-- Move Right/Down -->
                                        <button type="button" @click="moveRight(index)" :disabled="index === galleryImages.length - 1" class="w-5 h-5 rounded border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-[8px] disabled:opacity-30 disabled:cursor-not-allowed" title="Mută la dreapta">
                                            ▶️
                                        </button>
                                    </div>
                                    <!-- Delete from list -->
                                    <button type="button" @click="removeGalleryImage(index)" class="w-5 h-5 rounded border border-rose-100 bg-rose-50 text-rose-700 hover:bg-rose-100 flex items-center justify-center text-[8px]" title="Șterge din galerie">
                                        ❌
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="galleryImages.length === 0" class="py-12 border border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center text-center space-y-2 bg-slate-50/20 select-none">
                    <span class="text-2xl">📸</span>
                    <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Galerie goală</h4>
                    <p class="text-[9px] text-slate-400 font-semibold max-w-xs leading-relaxed">Nu a fost selectată nicio imagine pentru galeria acestei lucrări. Folosește butonul de mai sus pentru a selecta fișiere.</p>
                </div>
            </div>
            
            <!-- SEO cockpit metadata override -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-50 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Meta Taguri SEO & Snippet Previzualizare</h3>
                    <span class="text-[10px] text-slate-400 mt-2 font-medium block">Personalizează felul în care această lucrare este afișată și indexată pe Google</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="meta_title" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Meta Title Override</label>
                        <input type="text" name="meta_title" id="meta_title" x-model="metaTitle" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none transition-all font-semibold">
                        <div class="flex justify-between text-[8px] font-bold text-slate-400">
                            <span>Recomandat: max 60 caractere</span>
                            <span :class="metaTitle.length > 60 ? 'text-amber-600' : 'text-emerald-700'"><span x-text="metaTitle.length"></span> / 60</span>
                        </div>
                    </div>

                    <div class="flex items-center h-10 pt-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-[10px] font-bold text-slate-650">
                            <input type="checkbox" name="noindex" value="1" {{ old('noindex', $project->noindex ?? false) ? 'checked' : '' }} class="w-4 h-4 text-emerald-800 rounded border-slate-200">
                            Exclude această lucrare de pe Google (noindex)
                        </label>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="meta_description" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Meta Description Override</label>
                    <textarea name="meta_description" id="meta_description" rows="2" x-model="metaDescription" class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none transition-all font-semibold leading-relaxed"></textarea>
                    <div class="flex justify-between text-[8px] font-bold text-slate-400">
                        <span>Recomandat: max 160 caractere</span>
                        <span :class="metaDescription.length > 160 ? 'text-amber-600' : 'text-emerald-700'"><span x-text="metaDescription.length"></span> / 160</span>
                    </div>
                </div>

                <!-- Google Preview -->
                <div class="border-t border-slate-50 pt-4 space-y-3">
                    <strong class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Previzualizare Google Search</strong>
                    <div class="p-5 border border-slate-150/40 rounded-2xl bg-white space-y-2 max-w-xl">
                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold truncate leading-none">
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-50 border border-slate-200/50 flex items-center justify-center text-[7px]">🌐</span>
                            <span>{{ url('/') }}/proiecte/<span x-text="slug"></span></span>
                        </div>
                        <h3 class="text-sm font-bold text-[#1a0dab] hover:underline cursor-pointer leading-tight pt-1" x-text="metaTitle ? metaTitle : (title ? title + ' - Optera' : 'Titlu SEO Implicit')"></h3>
                        <p class="text-[11px] text-[#4d5156] font-medium leading-relaxed break-words" x-text="metaDescription ? metaDescription : 'Descriere implicită generată automat de Google dacă nu introduci valoruri custom...'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Sidebar params -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="border-b border-slate-50 pb-3">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Parametri Publicare</h3>
                </div>

                <!-- Status Select -->
                <div class="flex flex-col gap-1.5">
                    <label for="status" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Stare Publicare</label>
                    <select name="status" id="status" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold cursor-pointer">
                        <option value="draft" {{ old('status', $project->status ?? '') === 'draft' ? 'selected' : '' }}>📝 Ciornă (Draft)</option>
                        <option value="published" {{ old('status', $project->status ?? 'published') === 'published' ? 'selected' : '' }}>🟢 Publicat (Published)</option>
                        <option value="archived" {{ old('status', $project->status ?? '') === 'archived' ? 'selected' : '' }}>🔴 Arhivat / Ascuns</option>
                    </select>
                </div>

                <!-- Sort Order & Featured -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="sort_order" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Ordine Sortare</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold font-mono" min="0">
                    </div>
                    <div class="flex items-center pt-4 justify-center">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-[10px] font-bold text-slate-650">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }} class="w-4 h-4 text-emerald-800 rounded border-slate-200">
                            Afișează ca Recomandat
                        </label>
                    </div>
                </div>

                <!-- Featured Image Selector (Mediateca Integration) -->
                <div class="flex flex-col gap-1.5 pt-2">
                    <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Imagine Reprezentativă / Copertă</label>
                    <div class="relative">
                        <input type="text" name="featured_image" x-model="featuredImage" placeholder="Alege imaginea..." class="h-10 pl-3 pr-10 text-[10px] bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-semibold w-full" readonly>
                        <button type="button" @click="openMediaSelector('featured')" class="absolute right-2 top-2 h-6 px-2 text-[9px] font-bold bg-slate-150 border border-slate-200 rounded-md hover:bg-slate-200 transition-colors">
                            📁 Mediatecă
                        </button>
                    </div>
                    
                    <!-- Thumbnail preview -->
                    <div class="mt-2 aspect-video bg-slate-50 border border-slate-150/40 rounded-xl overflow-hidden relative flex items-center justify-center">
                        <template x-if="featuredImage">
                            <img :src="'/storage/' + featuredImage" alt="Preview Image" class="max-w-full max-h-full object-contain">
                        </template>
                        <template x-if="!featuredImage">
                            <span class="text-[8px] text-slate-400 font-bold">Nicio imagine selectată</span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Submit card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
                <button type="submit" class="w-full px-5 h-11 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                    💾 {{ isset($project) ? 'Salvează Actualizările' : 'Publică Lucrarea Nouă' }}
                </button>
                
                <a href="{{ route('admin.projects.index') }}" class="w-full px-5 h-10 text-xs font-bold text-slate-650 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 flex items-center justify-center transition-all">
                    Cancel
                </a>
            </div>
        </div>
    </form>

    <!-- MEDIATECA ASSETS SELECTOR MODAL -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 animate-fade-in" style="display: none;" @keydown.escape.window="showModal = false">
        <div class="bg-white rounded-3xl border border-slate-150 w-full max-w-4xl p-6 shadow-2xl space-y-5 animate-scale-up" @click.away="showModal = false">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                        Alege imagine pentru <span class="text-emerald-850" x-text="modalMode === 'featured' ? 'Copertă' : 'Galerie Foto'"></span>
                    </h3>
                    <span class="text-[9px] text-slate-400 font-semibold mt-1 block">Alege imaginea dorită din fișierele Mediatecii</span>
                </div>
                <button type="button" @click="showModal = false" class="w-6 h-6 rounded-full border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-xs focus:outline-none">
                    ✕
                </button>
            </div>

            <!-- Modal Folder Selector Filters -->
            <div class="flex flex-wrap gap-2 text-[10px] font-bold text-slate-550 border-b border-slate-50 pb-3">
                <button type="button" @click="changeModalFolder('projects')" :class="modalFolder === 'projects' ? 'text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100' : 'px-2.5 py-1'" class="transition-colors focus:outline-none">📁 projects</button>
                <button type="button" @click="changeModalFolder('general')" :class="modalFolder === 'general' ? 'text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100' : 'px-2.5 py-1'" class="transition-colors focus:outline-none">📁 general</button>
                <button type="button" @click="changeModalFolder('services')" :class="modalFolder === 'services' ? 'text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100' : 'px-2.5 py-1'" class="transition-colors focus:outline-none">🛠️ services</button>
            </div>

            <!-- Modal Grid Files explorer -->
            <div class="max-h-[350px] overflow-y-auto min-h-[250px] relative">
                <!-- Spinner -->
                <div x-show="loadingFiles" class="absolute inset-0 bg-white/70 flex items-center justify-center z-10" style="display: none;">
                    <div class="w-8 h-8 rounded-full border-4 border-emerald-50 border-t-emerald-800 animate-spin"></div>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                    <template x-for="file in modalFiles" :key="file.id">
                        <div class="border border-slate-100 rounded-2xl p-2 bg-white hover:border-emerald-800 cursor-pointer shadow-sm hover:shadow transition-all flex flex-col justify-between" @click="selectMedia(file.path)">
                            <div class="aspect-square bg-slate-50 border border-slate-100 rounded-xl overflow-hidden flex items-center justify-center">
                                <img :src="'/storage/' + (file.thumbnail_path ? file.thumbnail_path : file.path)" :alt="file.original_name" class="max-w-full max-h-full object-cover">
                            </div>
                            <span class="text-[8px] font-bold text-slate-800 truncate block mt-1.5 text-center" x-text="file.original_name"></span>
                        </div>
                    </template>
                    <template x-if="modalFiles.length === 0">
                        <div class="col-span-full py-16 text-center text-xs text-slate-450 font-medium select-none">
                            Nu s-au găsit imagini în acest folder.
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<!-- TinyMCE CDN integration -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '#full_description',
            height: 350,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });

    function projectForm(initialData, initialGallery) {
        return {
            title: initialData ? initialData.title : '',
            slug: initialData ? initialData.slug : '',
            slugLocked: initialData ? true : false,
            featuredImage: initialData ? initialData.featured_image : '',
            metaTitle: initialData ? (initialData.meta_title || '') : '',
            metaDescription: initialData ? (initialData.meta_description || '') : '',
            category: initialData ? initialData.category : 'Rezidențial',
            locality: initialData ? initialData.locality : 'Câmpulung Moldovenesc',
            
            galleryImages: initialGallery || [],
            
            showModal: false,
            modalMode: 'featured', // 'featured' or 'gallery'
            loadingFiles: false,
            modalFolder: 'projects',
            modalFiles: [],

            onTitleInput() {
                if (!this.slugLocked) {
                    this.slug = this.generateSlug(this.title);
                }
            },

            generateSlug(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start
                    .replace(/-+$/, '');            // Trim - from end
            },

            openMediaSelector(mode) {
                this.modalMode = mode;
                this.showModal = true;
                this.fetchModalFiles();
            },

            changeModalFolder(folder) {
                this.modalFolder = folder;
                this.fetchModalFiles();
            },

            fetchModalFiles() {
                this.loadingFiles = true;
                fetch(`/admin/media?folder=${this.modalFolder}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.loadingFiles = false;
                    if (data.success) {
                        this.modalFiles = data.files;
                    }
                })
                .catch(err => {
                    this.loadingFiles = false;
                    console.error('Eroare rețea la încărcare Mediatecă.');
                });
            },

            selectMedia(path) {
                if (this.modalMode === 'featured') {
                    this.featuredImage = path;
                    this.showModal = false;
                } else if (this.modalMode === 'gallery') {
                    if (!this.galleryImages.includes(path)) {
                        this.galleryImages.push(path);
                    }
                    this.showModal = false;
                }
            },

            removeGalleryImage(index) {
                this.galleryImages.splice(index, 1);
            },

            moveLeft(index) {
                if (index > 0) {
                    let item = this.galleryImages[index];
                    this.galleryImages.splice(index, 1);
                    this.galleryImages.splice(index - 1, 0, item);
                }
            },

            moveRight(index) {
                if (index < this.galleryImages.length - 1) {
                    let item = this.galleryImages[index];
                    this.galleryImages.splice(index, 1);
                    this.galleryImages.splice(index + 1, 0, item);
                }
            }
        }
    }
</script>
@endsection
