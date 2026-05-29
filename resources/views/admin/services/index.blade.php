@extends('layouts.admin')

@section('title', 'Servicii Supraveghere')
@section('section_title', 'Administrare Servicii')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Controls & Filters panel -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.services.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-grow">
            <!-- Search -->
            <div class="relative w-full md:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Caută serviciu..." class="w-full h-10 pl-3 pr-8 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:outline-none transition-all font-semibold">
                <button type="submit" class="absolute right-2.5 top-3 text-[10px]">🔍</button>
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold cursor-pointer">
                <option value="">Toate stările</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Ciornă (Draft)</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicat (Published)</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Arhivat</option>
            </select>

            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.services.index') }}" class="text-[10px] font-bold text-red-650 hover:underline">
                    ❌ Șterge filtrele
                </a>
            @endif
        </form>

        <div class="flex shrink-0">
            <a href="{{ route('admin.services.create') }}" class="px-5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                ➕ Adaugă Serviciu Nou
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
                        <th class="py-4 px-6">Serviciu</th>
                        <th class="py-4 px-6">Slug / Adresă</th>
                        <th class="py-4 px-6 text-center">Featured</th>
                        <th class="py-4 px-6 text-center">Stare</th>
                        <th class="py-4 px-6 text-right">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs font-semibold text-slate-700">
                    @forelse($services as $service)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <!-- Sort Order -->
                            <td class="py-4 px-6 font-mono text-[10px] text-slate-400">
                                #{{ $service->sort_order }}
                            </td>
                            
                            <!-- Title & Icon -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm" title="Icon: {{ $service->icon_key }}">
                                        @if($service->icon_key === 'home') 🏠
                                        @elseif($service->icon_key === 'shield') 🛡️
                                        @elseif($service->icon_key === 'wrench') 🔧
                                        @elseif($service->icon_key === 'smartphone') 📱
                                        @elseif($service->icon_key === 'tool') 🛠️
                                        @elseif($service->icon_key === 'refresh-cw') 🔄
                                        @else 📁
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-xs">{{ $service->title }}</span>
                                        <span class="text-[9px] text-slate-400 mt-0.5 leading-relaxed font-medium truncate max-w-xs">{{ $service->short_description }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Slug / Live preview URL -->
                            <td class="py-4 px-6 font-mono text-[10px] text-slate-450">
                                <span class="block">/servicii/{{ $service->slug }}</span>
                                @if($service->status === 'published')
                                    <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="text-[9px] font-bold text-emerald-800 hover:underline mt-0.5 inline-block">
                                        🔗 Vezi live pe site
                                    </a>
                                @endif
                            </td>

                            <!-- Featured Status -->
                            <td class="py-4 px-6 text-center">
                                @if($service->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-100">
                                        ⭐ Featured
                                    </span>
                                @else
                                    <span class="text-slate-350 text-[10px] font-medium">-</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6 text-center">
                                @if($service->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        Publicat
                                    </span>
                                @elseif($service->status === 'draft')
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
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center transition-colors" title="Editează">
                                        ✏️
                                    </a>
                                    
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Sigur doriți să mutați acest serviciu în arhivă?');">
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
                            <td colspan="6" class="py-12 text-center text-xs text-slate-450 font-medium bg-slate-50/20">
                                Nu a fost găsit niciun serviciu în baza de date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-150/40">
                {{ $services->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
