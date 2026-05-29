@extends('layouts.admin')

@section('title', 'Media Library')
@section('section_title', 'Biblioteca Fișiere Media')

@section('content')
<div class="space-y-6" x-data="mediaUploader()">

    <!-- Header bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex flex-col">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Management Biblioteca Media</h3>
            <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Încarcă, organizează și optimizează imaginile în format WebP</span>
        </div>
    </div>

    <!-- Alert feeds -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif
    <div x-show="errorMessage" x-text="errorMessage" class="p-4 bg-rose-50 text-rose-850 border border-rose-250/50 rounded-2xl text-xs font-semibold animate-fade-in" style="display: none;"></div>
    <div x-show="successMessage" x-text="successMessage" class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold animate-fade-in" style="display: none;"></div>

    <!-- Folders directory explorer (Bento styles) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($folders as $folder)
            <a href="{{ route('admin.media', ['folder' => $folder]) }}" class="bg-white border border-slate-100 hover:border-emerald-800/20 rounded-2xl p-4 text-center shadow-sm hover:shadow transition-all group flex flex-col items-center justify-center {{ $activeFolder === $folder ? 'ring-1 ring-emerald-800 bg-emerald-50/5' : '' }}">
                <span class="text-2xl group-hover:scale-110 transition-transform select-none">📁</span>
                <span class="text-[10px] font-extrabold text-slate-800 mt-2 block uppercase tracking-wider">{{ $folder }}</span>
            </a>
        @endforeach
    </div>

    <!-- Dual Split layout: drag-and-drop uploader + media grid explorer -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 sm:gap-8 items-start">
        
        <!-- Left 1 Col: Secure drag uploader card -->
        <div class="xl:col-span-1 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Încărcare Imagini</h4>
                <span class="text-[8px] text-slate-400 mt-1 block leading-normal">Folder activ: <strong class="text-slate-800 uppercase">{{ $activeFolder }}</strong></span>
            </div>

            <!-- Drag area container -->
            <div @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop($event)" :class="dragOver ? 'border-emerald-800 bg-emerald-50/10' : 'border-slate-200'" class="border-2 border-dashed rounded-2xl p-6 text-center hover:bg-slate-50/30 transition-all cursor-pointer relative group flex flex-col items-center justify-center min-h-[160px]" @click="$refs.fileInput.click()">
                <input type="file" x-ref="fileInput" @change="handleFileSelect($event)" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp">
                
                <div class="space-y-2 select-none" x-show="!uploading">
                    <span class="text-2xl">📤</span>
                    <h5 class="text-[10px] font-bold text-slate-800">Trage imaginea aici</h5>
                    <p class="text-[8px] text-slate-400 font-semibold leading-normal">Sau dă clic pentru a naviga în fișiere</p>
                </div>

                <!-- Upload loading spinner -->
                <div class="space-y-3" x-show="uploading" style="display: none;">
                    <div class="w-8 h-8 rounded-full border-4 border-emerald-50 border-t-emerald-800 animate-spin mx-auto"></div>
                    <span class="text-[9px] font-bold text-emerald-850">Se uploadează și se convertește...</span>
                </div>
            </div>

            <!-- Limits specifications info alert -->
            <div class="p-4 bg-slate-50 border border-slate-150/40 rounded-2xl space-y-2">
                <h5 class="text-[9px] font-extrabold text-slate-450 uppercase tracking-wider block">Reguli de Securitate</h5>
                <ul class="text-[8px] text-slate-405 font-bold space-y-1.5 list-disc pl-4 leading-normal">
                    <li>Tipuri acceptate: <strong>JPG, PNG, JPEG, WEBP</strong></li>
                    <li>Să nu depășească <strong>5 MB</strong> per fișier</li>
                    <li>Limită rezoluție maximă: <strong>4000x4000 px</strong></li>
                    <li class="text-red-750">Fisierele SVG sunt STRICT blocate pentru siguranță</li>
                </ul>
            </div>
        </div>

        <!-- Right 3 Cols: Images preview grid board -->
        <div class="xl:col-span-3 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex flex-col">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Imagini din folderul {{ $activeFolder }}</h3>
                    <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Faceți clic pe detalii pentru a copia calea fișierului sau a-l șterge</span>
                </div>

                <!-- Search form -->
                <form action="{{ route('admin.media') }}" method="GET" class="inline">
                    <input type="hidden" name="folder" value="{{ $activeFolder }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Caută după nume..." class="h-8.5 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                </form>
            </div>

            <!-- Assets Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($files as $file)
                    <div class="border border-slate-100 rounded-2xl p-2.5 bg-white relative group shadow-sm hover:shadow transition-shadow flex flex-col justify-between min-h-[170px]">
                        <!-- Image render -->
                        <div class="aspect-square bg-slate-50 border border-slate-100 rounded-xl overflow-hidden relative flex items-center justify-center select-none">
                            <img src="{{ $file->thumbnail_url }}" alt="{{ $file->original_name }}" class="max-w-full max-h-full object-cover">
                            
                            <!-- Overlay tools -->
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button type="button" @click="copyPath('{{ $file->path }}')" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs" title="Copiază calea fișierului">
                                    🔗
                                </button>
                                
                                <form action="{{ route('admin.media.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Sigur doriți să ștergeți permanent acest fișier de pe disc?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-800/20 hover:bg-red-800/40 text-red-100 flex items-center justify-center text-xs" title="Șterge permanent">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Details card segment -->
                        <div class="mt-2 text-left">
                            <span class="text-[9px] font-bold text-slate-800 truncate block max-w-full" title="{{ $file->original_name }}">
                                {{ $file->original_name }}
                            </span>
                            <span class="text-[8px] text-slate-400 font-semibold mt-1 block">
                                @if($file->width && $file->height)
                                    {{ $file->width }}x{{ $file->height }} px • 
                                @endif
                                {{ number_format($file->size / 1024, 0) }} KB
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-xs text-slate-450 font-medium">
                        Nu s-au găsit fișiere în acest folder. Trageți fișiere în stânga pentru a le încărca!
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-4 border-t border-slate-50">
                {{ $files->links() }}
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function mediaUploader() {
        return {
            dragOver: false,
            uploading: false,
            errorMessage: '',
            successMessage: '',
            activeFolder: '{{ $activeFolder }}',

            handleFileSelect(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    this.processUpload(files[0]);
                }
            },

            handleDrop(e) {
                this.dragOver = false;
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.processUpload(files[0]);
                }
            },

            processUpload(file) {
                // Client-side secure validation blocks SVGs and size check
                if (file.name.toLowerCase().endsWith('.svg') || file.type === 'image/svg+xml') {
                    this.showError('Securitate blocată: Fisierele SVG sunt STRICT interzise pentru a preveni atacurile XSS.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    this.showError('Eroare dimensiune: Fișierul depășește limita maximă admisă de 5 MB.');
                    return;
                }

                this.uploading = true;
                this.errorMessage = '';
                this.successMessage = '';

                const formData = new FormData();
                formData.append('file', file);
                formData.append('folder', this.activeFolder);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin.media.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.uploading = false;
                    if (data.success) {
                        this.showSuccess(data.message);
                        // Refresh page to show new WebP card after 1 second
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showError(data.message || 'A apărut o eroare la încărcare.');
                    }
                })
                .catch(err => {
                    this.uploading = false;
                    this.showError('Eroare de rețea. Nu s-a putut realiza conexiunea cu cPanel.');
                });
            },

            showError(msg) {
                this.errorMessage = msg;
                setTimeout(() => this.errorMessage = '', 5000);
            },

            showSuccess(msg) {
                this.successMessage = msg;
                setTimeout(() => this.successMessage = '', 5000);
            },

            copyPath(path) {
                navigator.clipboard.writeText(path).then(() => {
                    alert('Calea fișierului a fost copiată în clipboard: ' + path);
                });
            }
        }
    }
</script>
@endsection
