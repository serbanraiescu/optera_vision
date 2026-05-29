@extends('layouts.admin')

@section('title', 'Cereri Ofertă (CRM)')
@section('section_title', 'Administrare CRM Leads')

@section('content')
<div class="space-y-6">

    <!-- Header bar with export and quick summaries -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex flex-col">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Panou Administrare Cereri</h3>
            <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Administrează, repartizează și gestionează ofertele primite online</span>
        </div>
        
        @if(auth()->user()->role !== 'technician')
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quotes.export') }}" class="inline-flex items-center justify-center px-4.5 h-10 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm focus:outline-none">
                📥 Descarcă CSV Excel
            </a>
        </div>
        @endif
    </div>

    <!-- Multi-Filter Board component -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5">
        <form action="{{ route('admin.quotes') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Search bar -->
                <div class="flex flex-col gap-1.5">
                    <label for="search" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Caută Client</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nume, telefon, email..." class="h-10 px-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all placeholder:text-slate-400 font-semibold">
                </div>

                <!-- Status filter -->
                <div class="flex flex-col gap-1.5">
                    <label for="status" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Stadiu CRM</label>
                    <select name="status" id="status" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all font-semibold">
                        <option value="">Toate stadiile</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Setup Type filter -->
                <div class="flex flex-col gap-1.5">
                    <label for="setup_type" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Tip Sistem</label>
                    <select name="setup_type" id="setup_type" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all font-semibold">
                        <option value="">Toate sistemele</option>
                        <option value="new" {{ request('setup_type') === 'new' ? 'selected' : '' }}>Sistem Nou</option>
                        <option value="upgrade" {{ request('setup_type') === 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                    </select>
                </div>

                <!-- Location Type filter -->
                <div class="flex flex-col gap-1.5">
                    <label for="location_type" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Tip Locație</label>
                    <select name="location_type" id="location_type" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all font-semibold">
                        <option value="">Toate locațiile</option>
                        <option value="rezidential" {{ request('location_type') === 'rezidential' ? 'selected' : '' }}>Rezidențial</option>
                        <option value="comercial" {{ request('location_type') === 'comercial' ? 'selected' : '' }}>Comercial</option>
                        <option value="industrial" {{ request('location_type') === 'industrial' ? 'selected' : '' }}>Industrial</option>
                        <option value="public" {{ request('location_type') === 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>

                <!-- Assigned Responsibility (Only Visible to Admin/Operator) -->
                @if(auth()->user()->role !== 'technician')
                <div class="flex flex-col gap-1.5">
                    <label for="assigned_to" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Responsabil</label>
                    <select name="assigned_to" id="assigned_to" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all font-semibold">
                        <option value="">Toți utilizatorii</option>
                        @foreach($assignees as $staff)
                            <option value="{{ $staff->id }}" {{ request('assigned_to') == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ ucfirst($staff->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Sorting and submission triggers -->
                <div class="flex flex-col gap-1.5">
                    <label for="sort" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Ordonează</label>
                    <select name="sort" id="sort" class="h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all font-semibold">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Cele mai noi</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cele mai vechi</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <a href="{{ route('admin.quotes') }}" class="inline-flex items-center justify-center px-4 h-9.5 text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:text-slate-800 rounded-lg transition-all focus:outline-none">
                    Resetează Filtre
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-5 h-9.5 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg transition-all shadow-md shadow-emerald-900/5 focus:outline-none">
                    Filtrează Rezultate
                </button>
            </div>
        </form>
    </div>

    <!-- Quote requests listing board -->
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm shadow-slate-100/5">
        
        <!-- Desktop Grid View (Visible on sm and up) -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-extrabold text-slate-450 uppercase tracking-wider">
                        <th class="py-4 pl-6">Client</th>
                        <th class="py-4">Localitate</th>
                        <th class="py-4">Tip Proiect</th>
                        <th class="py-4">Detalii</th>
                        <th class="py-4">Buget Estimat</th>
                        <th class="py-4">Stadiu</th>
                        @if(auth()->user()->role !== 'technician')
                            <th class="py-4">Responsabil</th>
                        @endif
                        <th class="py-4">Dată Primire</th>
                        <th class="py-4 text-right pr-6">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold divide-y divide-slate-50 text-slate-700">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-slate-50/20 transition-colors {{ $quote->is_important ? 'bg-amber-50/10 hover:bg-amber-50/20' : '' }}">
                            <!-- Client Info -->
                            <td class="py-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.quotes.show', $quote->id) }}" class="font-bold text-slate-900 hover:text-emerald-850 transition-all flex items-center gap-1.5">
                                            {{ $quote->name }}
                                            @if($quote->is_important)
                                                <span class="text-amber-500 text-xs" title="Cerere Importantă">★</span>
                                            @endif
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-semibold mt-1">{{ $quote->phone }} • {{ $quote->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Location Locality -->
                            <td class="py-4 text-slate-600 font-bold">
                                {{ $quote->locality ?: '-' }}
                            </td>

                            <!-- Location & Setup types -->
                            <td class="py-4">
                                <span class="capitalize text-slate-550 font-medium">{{ $quote->location_type }}</span>
                                <span class="text-[9px] text-slate-400 font-bold block mt-1 uppercase">{{ $quote->is_upgrade ? 'Upgrade' : 'Sistem Nou' }}</span>
                            </td>

                            <!-- Camera Details -->
                            <td class="py-4 text-slate-650">
                                📹 <strong class="text-slate-800">{{ $quote->camera_count }}</strong> camere
                            </td>

                            <!-- dynamic pricing value -->
                            <td class="py-4 font-mono font-bold text-slate-900">
                                {{ number_format($quote->estimated_value, 0, ',', '.') }} RON
                            </td>

                            <!-- Status color badge -->
                            <td class="py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase border {{ $quote->status->colorClass() }}">
                                    {{ $quote->status->label() }}
                                </span>
                            </td>

                            <!-- Assigned staff (Admins only) -->
                            @if(auth()->user()->role !== 'technician')
                            <td class="py-4 font-medium text-slate-550">
                                @if($quote->assignee)
                                    <span class="text-slate-850 font-bold">👤 {{ $quote->assignee->name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Nerepartizat</span>
                                @endif
                            </td>
                            @endif

                            <!-- Received date -->
                            <td class="py-4 text-slate-400 font-medium">
                                {{ $quote->created_at->format('d.m.Y H:i') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.quotes.show', $quote->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-500 border border-slate-200/40 flex items-center justify-center transition-colors" title="Deschide CRM">
                                        👁️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-450 font-medium bg-slate-50/10">
                                Nu s-au găsit cereri de ofertă care să corespundă criteriilor selectate.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Visible on small screens) -->
        <div class="block sm:hidden divide-y divide-slate-100 p-4 space-y-4">
            @forelse($quotes as $quote)
                <div class="bg-white border border-slate-100 rounded-2xl p-4 space-y-3 relative hover:shadow-md transition-shadow {{ $quote->is_important ? 'border-amber-250 bg-amber-50/5' : '' }}">
                    @if($quote->is_important)
                        <span class="absolute top-3 right-3 text-amber-500 text-base" title="Important">★</span>
                    @endif

                    <!-- Header Segment -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 font-bold flex items-center justify-center text-xs">
                            {{ substr($quote->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <a href="{{ route('admin.quotes.show', $quote->id) }}" class="text-xs font-bold text-slate-900 block leading-tight">
                                {{ $quote->name }}
                            </a>
                            <span class="text-[9px] text-slate-400 mt-1 font-semibold leading-none">{{ $quote->phone }}</span>
                        </div>
                    </div>

                    <!-- Details Segment -->
                    <div class="grid grid-cols-2 gap-3 text-[10px] border-t border-b border-slate-50 py-3 font-semibold text-slate-650">
                        <div>
                            <span class="text-slate-400 font-medium block">Localitate</span>
                            <span class="text-slate-800 font-bold mt-0.5 block">{{ $quote->locality ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Tip Locație/Sistem</span>
                            <span class="text-slate-800 mt-0.5 block capitalize">{{ $quote->location_type }} • {{ $quote->is_upgrade ? 'Upgrade' : 'Nou' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Număr Camere</span>
                            <span class="text-slate-800 mt-0.5 block">📹 {{ $quote->camera_count }} camere</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block">Buget Estimat</span>
                            <span class="text-emerald-800 font-extrabold mt-0.5 block">{{ number_format($quote->estimated_value, 0, ',', '.') }} RON</span>
                        </div>
                    </div>

                    <!-- Status and responsibility segment -->
                    <div class="flex items-center justify-between pt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-extrabold tracking-wide uppercase border {{ $quote->status->colorClass() }}">
                            {{ $quote->status->label() }}
                        </span>
                        
                        <div class="flex items-center gap-2">
                            @if(auth()->user()->role !== 'technician')
                                @if($quote->assignee)
                                    <span class="text-[9px] font-bold text-slate-605">👤 {{ $quote->assignee->name }}</span>
                                @else
                                    <span class="text-[9px] font-semibold text-slate-400 italic">Nerepartizat</span>
                                @endif
                            @endif

                            <a href="{{ route('admin.quotes.show', $quote->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs" title="Detalii">
                                👁️
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-xs text-slate-450 font-medium">
                    Nu s-au găsit cereri de ofertă.
                </div>
            @endforelse
        </div>

        <!-- Paginated Board Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/10">
            {{ $quotes->links() }}
        </div>
    </div>

</div>
@endsection
