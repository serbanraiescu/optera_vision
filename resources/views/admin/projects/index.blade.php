@extends('layouts.admin')

@section('title', 'Portofoliu Lucrări')
@section('section_title', 'Administrare Portofoliu')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Controls & Filters panel -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.projects.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-grow">
            <!-- Search -->
            <div class="relative w-full md:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Caută lucrare, localitate, categorie..." class="w-full h-10 pl-3 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold">
                <button type="submit" class="absolute right-2.5 top-3 text-[10px]">🔍</button>
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold cursor-pointer">
                <option value="">Toate stările</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Ciornă (Draft)</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicat (Published)</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Arhivat</option>
            </select>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold cursor-pointer">
                <option value="">Toate categoriile</option>
                <option value="Rezidențial" {{ request('category') === 'Rezidențial' ? 'selected' : '' }}>Rezidențial</option>
                <option value="Comercial" {{ request('category') === 'Comercial' ? 'selected' : '' }}>Comercial</option>
                <option value="Industrial" {{ request('category') === 'Industrial' ? 'selected' : '' }}>Industrial</option>
            </select>

            @if(request()->anyFilled(['search', 'status', 'category']))
                <a href="{{ route('admin.projects.index') }}" class="text-[10px] font-bold text-red-650 hover:underline">
                    ❌ Șterge filtrele
                </a>
            @endif
        </form>

        <div class="flex shrink-0">
            <a href="{{ route('admin.projects.create') }}" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                ➕ Adaugă Lucrare Nouă
            </a>
        </div>
    </div>

    <!-- Data Grid -->
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">
                        <th class="py-4 px-6">Poz. (Sort)</th>
                        <th class="py-4 px-6">Copertă</th>
                        <th class="py-4 px-6">Lucrare / Portofoliu</th>
                        <th class="py-4 px-6">Slug / Adresă</th>
                        <th class="py-4 px-6 text-center">Featured</th>
                        <th class="py-4 px-6 text-center">Stare</th>
                        <th class="py-4 px-6 text-right">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs font-semibold text-slate-700">
                    @forelse($projects as $project)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <!-- Sort Order -->
                            <td class="py-4 px-6 font-mono text-[10px] text-slate-400">
                                #{{ $project->sort_order }}
                            </td>
                            
                            <!-- Featured Image Thumbnail -->
                            <td class="py-4 px-6">
                                <div class="w-12 h-8 rounded-lg bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                                    @if($project->featured_image)
                                        <img src="{{ Str::startsWith($project->featured_image, 'http') ? $project->featured_image : asset('storage/' . $project->featured_image) }}" alt="Preview" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[8px] text-slate-400 font-bold">Fără foto</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Title, Category & Locality -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col space-y-1">
                                    <span class="font-bold text-slate-800 text-xs">{{ $project->title }}</span>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center px-1.5 py-0.2 bg-slate-100 text-slate-600 rounded text-[9px] font-bold">
                                            🏷️ {{ $project->category }}
                                        </span>
                                        <span class="inline-flex items-center px-1.5 py-0.2 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold">
                                            📍 {{ $project->locality }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Slug / Live preview URL -->
                            <td class="py-4 px-6 font-mono text-[10px] text-slate-450">
                                <span class="block">/proiecte/{{ $project->slug }}</span>
                                @if($project->status === 'published')
                                    <a href="{{ route('projects.show', $project->slug) }}" target="_blank" class="text-[9px] font-bold text-emerald-800 hover:underline mt-0.5 inline-block">
                                        🔗 Vezi live pe site
                                    </a>
                                @endif
                            </td>

                            <!-- Featured Status -->
                            <td class="py-4 px-6 text-center">
                                @if($project->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-100">
                                        ⭐ Featured
                                    </span>
                                @else
                                    <span class="text-slate-350 text-[10px] font-medium">-</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6 text-center">
                                @if($project->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        Publicat
                                    </span>
                                @elseif($project->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        Ciornă
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-800 border border-rose-100">
                                        Arhivat
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center transition-colors" title="Editează">
                                        ✏️
                                    </a>
                                    
                                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Sigur doriți să mutați acest proiect în arhivă?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-red-50 hover:text-red-650 hover:border-red-100 flex items-center justify-center transition-colors" title="Șterge / Arhivează">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-slate-450 font-medium bg-slate-50/20">
                                Nu a fost găsită nicio lucrare în baza de date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-150/40">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
