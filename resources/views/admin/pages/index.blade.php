@extends('layouts.admin')

@section('title', 'Pagini CMS')
@section('section_title', 'Administrare Pagini CMS')

@section('content')
<div class="space-y-6">

    <!-- Header segment -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex flex-col">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Pagini Web & Landing Pages</h3>
            <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Creează pagini de SEO local, documente legale ANPC/GDPR sau resurse personalizate</span>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center justify-center px-4.5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                ➕ Adaugă Pagină Nouă
            </a>
        </div>
    </div>

    <!-- Alert feeds -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-250/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtering board -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5">
        <form action="{{ route('admin.pages.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
            <!-- Search title -->
            <div class="flex-grow flex flex-col gap-1.5">
                <label for="search" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Caută după Titlu / Slug</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Căutare titlu..." class="h-10 px-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
            </div>

            <!-- Page type filter -->
            <div class="flex flex-col gap-1.5 min-w-[160px]">
                <label for="type" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Tip Pagină</label>
                <select name="type" id="type" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                    <option value="">Toate tipurile</option>
                    <option value="legal" {{ request('type') === 'legal' ? 'selected' : '' }}>Legal (Politici)</option>
                    <option value="local_seo" {{ request('type') === 'local_seo' ? 'selected' : '' }}>SEO Local (Lander)</option>
                    <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Personalizată</option>
                </select>
            </div>

            <!-- Status filter -->
            <div class="flex flex-col gap-1.5 min-w-[140px]">
                <label for="status" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Stare Publicare</label>
                <select name="status" id="status" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                    <option value="">Toate stările</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Ciornă)</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicată</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Arhivată</option>
                </select>
            </div>

            <!-- Triggers -->
            <div class="flex md:self-end gap-2 pt-2 md:pt-0">
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center justify-center px-4 h-9.5 text-xs font-bold text-slate-550 bg-slate-50 border border-slate-200 hover:bg-slate-100 rounded-lg transition-all focus:outline-none">
                    Reset
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-5 h-9.5 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg transition-all shadow focus:outline-none">
                    Filtrează
                </button>
            </div>
        </form>
    </div>

    <!-- Pages List table -->
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm shadow-slate-100/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-extrabold text-slate-450 uppercase tracking-wider">
                        <th class="py-4 pl-6">Titlu Pagină</th>
                        <th class="py-4">Tip Pagină</th>
                        <th class="py-4">Cale Link (Slug)</th>
                        <th class="py-4">Tip Relație Master</th>
                        <th class="py-4">Status</th>
                        <th class="py-4">Ultima actualizare</th>
                        <th class="py-4 text-right pr-6">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold divide-y divide-slate-50 text-slate-700">
                    @forelse($pages as $p)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <!-- Title -->
                            <td class="py-4 pl-6">
                                <a href="{{ route('admin.pages.edit', $p->id) }}" class="font-bold text-slate-900 hover:text-emerald-850 transition-colors">
                                    📄 {{ $p->title }}
                                </a>
                            </td>

                            <!-- Page type -->
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded text-[8px] font-extrabold uppercase border
                                    @if($p->type === 'legal')
                                        bg-slate-100 text-slate-550 border-slate-200/50
                                    @elseif($p->type === 'local_seo')
                                        bg-emerald-50 text-emerald-800 border-emerald-100
                                    @else
                                        bg-blue-50 text-blue-800 border-blue-100
                                    @endif
                                ">
                                    {{ $p->type === 'local_seo' ? 'SEO Local' : ucfirst($p->type) }}
                                </span>
                            </td>

                            <!-- Link / Slug -->
                            <td class="py-4 font-mono text-[10px] text-slate-500">
                                @if($p->status === 'published')
                                    <a href="{{ url($p->slug) }}" target="_blank" class="hover:underline text-emerald-850">
                                        /{{ $p->slug }} ↗
                                    </a>
                                @else
                                    /{{ $p->slug }}
                                @endif
                            </td>

                            <!-- Master page relationship -->
                            <td class="py-4 font-medium text-slate-455">
                                @if($p->type === 'local_seo')
                                    @if($p->parent)
                                        <span class="text-slate-800">Child din master: <strong>{{ $p->parent->title }}</strong></span>
                                    @else
                                        <span class="text-emerald-700 font-extrabold">★ Master Template</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-medium italic">-</span>
                                @endif
                            </td>

                            <!-- Status color badge -->
                            <td class="py-4">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase border
                                    @if($p->status === 'published')
                                        bg-emerald-50 text-emerald-800 border-emerald-100
                                    @elseif($p->status === 'draft')
                                        bg-amber-50 text-amber-800 border-amber-100
                                    @else
                                        bg-slate-50 text-slate-650 border-slate-200/50
                                    @endif
                                ">
                                    {{ $p->status }}
                                </span>
                            </td>

                            <!-- Date update -->
                            <td class="py-4 text-slate-400 font-medium">
                                {{ $p->updated_at->format('d.m.Y H:i') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Duplication duplicate button -->
                                    <form action="{{ route('admin.pages.duplicate', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-200/40 flex items-center justify-center text-slate-500 transition-colors" title="Duplică Pagină">
                                            👯
                                        </button>
                                    </form>

                                    <!-- Edit page -->
                                    <a href="{{ route('admin.pages.edit', $p->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-200/40 flex items-center justify-center text-slate-500 transition-colors" title="Editează Pagină">
                                        ✏️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-450 font-medium bg-slate-50/10">
                                Nu s-au înregistrat pagini CMS în sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/10">
            {{ $pages->links() }}
        </div>
    </div>

</div>
@endsection
