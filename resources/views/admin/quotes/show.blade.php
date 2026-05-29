@extends('layouts.admin')

@section('title', 'Detalii Cerere #' . $quoteRequest->id)
@section('section_title', 'Fișă Lead CRM')

@section('content')
<div class="space-y-6 animate-fade-in">

    <!-- Top Navigation and Quick details -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quotes') }}" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/40 text-slate-500 hover:bg-slate-150 hover:text-slate-800 flex items-center justify-center transition-colors text-xs" title="Înapoi la CRM Board">
                ←
            </a>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Solicitare #{{ $quoteRequest->id }}</h3>
                    @if($quoteRequest->is_important)
                        <span class="text-amber-500 text-xs font-bold bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded-full flex items-center gap-1">
                            ★ Important
                        </span>
                    @endif
                </div>
                <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Primt la: {{ $quoteRequest->created_at->format('d.m.Y H:i') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Toggle Importance (Admins & Operators) -->
            @if(auth()->user()->role !== 'technician')
                <form action="{{ route('admin.quotes.important', $quoteRequest->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 h-9 text-xs font-bold rounded-xl border {{ $quoteRequest->is_important ? 'text-amber-700 bg-amber-50 border-amber-200/60 hover:bg-amber-100' : 'text-slate-550 bg-white border-slate-200 hover:bg-slate-50' }} transition-all focus:outline-none">
                        ★ {{ $quoteRequest->is_important ? 'Retrage Importanță' : 'Marchează ca Important' }}
                    </button>
                </form>
            @endif

            <!-- Soft Delete (Admins only) -->
            @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.quotes.destroy', $quoteRequest->id) }}" method="POST" class="inline" onsubmit="return confirm('Sigur doriți să arhivați această cerere de ofertă? Ea va fi ascunsă din board.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center px-4 h-9 text-xs font-bold text-red-750 bg-red-50 hover:bg-red-100/80 border border-red-100 rounded-xl transition-all focus:outline-none">
                        🗑️ Arhivează Cererea
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert notifications status feeds -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-850 border border-emerald-200/50 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-50 text-blue-850 border border-blue-200/50 rounded-2xl text-xs font-semibold">
            {{ session('info') }}
        </div>
    @endif

    <!-- Workspace main layout splits -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
        
        <!-- Left Column: Lead File contact card & technical configuration specs -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Client Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Date Client</h4>
                </div>
                
                <div class="space-y-3.5">
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Nume Client</span>
                        <strong class="text-slate-800 text-xs font-extrabold block mt-0.5">{{ $quoteRequest->name }}</strong>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Telefon</span>
                        <a href="tel:{{ $quoteRequest->phone }}" class="text-emerald-800 text-xs font-bold block mt-0.5 hover:underline">
                            📞 {{ $quoteRequest->phone }}
                        </a>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Email</span>
                        <a href="mailto:{{ $quoteRequest->email }}" class="text-slate-700 text-xs font-medium block mt-0.5 hover:underline">
                            ✉️ {{ $quoteRequest->email }}
                        </a>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Localitate</span>
                        <span class="text-slate-800 text-xs font-semibold block mt-0.5">{{ $quoteRequest->locality ?: 'Fără localitate' }}</span>
                    </div>
                </div>
            </div>

            <!-- Technical Configuration Card -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5 space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Configurație Solicitată</h4>
                    <span class="text-[8px] font-bold text-slate-450 bg-slate-50 border border-slate-200/40 px-1.5 py-0.5 rounded uppercase">
                        {{ $quoteRequest->lead_source ?: 'configurator' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Tip Locație</span>
                        <span class="text-slate-800 text-xs font-bold block mt-0.5 capitalize">{{ $quoteRequest->location_type }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Tip Proiect</span>
                        <span class="text-slate-800 text-xs font-bold block mt-0.5">{{ $quoteRequest->is_upgrade ? 'Upgrade Sistem' : 'Sistem Nou' }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Număr Camere</span>
                        <span class="text-slate-800 text-xs font-bold block mt-0.5">📹 {{ $quoteRequest->camera_count }} camere</span>
                    </div>
                    <div>
                        <span class="text-[9px] text-slate-400 font-medium block">Valoare Estimată</span>
                        <span class="text-emerald-800 text-xs font-extrabold block mt-0.5">{{ number_format($quoteRequest->estimated_value, 0, ',', '.') }} RON</span>
                    </div>
                </div>

                @if($quoteRequest->message)
                <div class="border-t border-slate-50 pt-4">
                    <span class="text-[9px] text-slate-400 font-medium block mb-1">Mesaj Client</span>
                    <p class="text-[10px] text-slate-600 bg-slate-50 border border-slate-150/40 p-3 rounded-xl leading-relaxed italic font-medium">
                        "{{ $quoteRequest->message }}"
                    </p>
                </div>
                @endif
            </div>

            <!-- CRM Active Actions Panel -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5 space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Acțiuni CRM</h4>
                </div>

                <!-- Update Status Form -->
                <form action="{{ route('admin.quotes.status', $quoteRequest->id) }}" method="POST" class="space-y-2">
                    @csrf
                    <label for="status" class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Stadiu Lead</label>
                    <div class="flex gap-2">
                        <select name="status" id="status" class="flex-grow h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" {{ $quoteRequest->status === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 bg-emerald-800 hover:bg-emerald-950 text-white rounded-xl text-xs font-bold transition-colors shadow focus:outline-none">
                            Salvează
                        </button>
                    </div>
                </form>

                <!-- Reassign Staff Form (Admins only) -->
                @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.quotes.assign', $quoteRequest->id) }}" method="POST" class="space-y-2 pt-2 border-t border-slate-50">
                    @csrf
                    <label for="assigned_to" class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Responsabil Alocat</label>
                    <div class="flex gap-2">
                        <select name="assigned_to" id="assigned_to" class="flex-grow h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none font-semibold transition-all">
                            <option value="">Nerepartizat</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ $quoteRequest->assigned_to === $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ ucfirst($member->role) }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 bg-emerald-800 hover:bg-emerald-950 text-white rounded-xl text-xs font-bold transition-colors shadow focus:outline-none">
                            Alocă
                        </button>
                    </div>
                    @if($quoteRequest->assigned_at)
                        <span class="text-[8px] text-slate-400 font-semibold block leading-none mt-1">
                            Atribuit la data: {{ $quoteRequest->assigned_at->format('d.m.Y H:i') }}
                        </span>
                    @endif
                </form>
                @endif

                <!-- Duplication-Safe Client conversion registry linkage widget -->
                @if(auth()->user()->role !== 'technician')
                <div class="pt-4 border-t border-slate-50 flex flex-col gap-2">
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Registru Clienți</span>
                    @if($quoteRequest->client_id)
                        <div class="p-3 bg-emerald-50/50 border border-emerald-100 rounded-2xl flex items-center justify-between">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[9px] text-slate-405 font-medium leading-none">✓ Client asociat</span>
                                <span class="text-xs font-bold text-slate-800 leading-none mt-1">{{ $quoteRequest->client->name }}</span>
                            </div>
                            <a href="{{ route('admin.clients', ['search' => $quoteRequest->client->phone]) }}" class="text-[9px] font-extrabold text-emerald-800 hover:underline">
                                Detalii →
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.clients.link', $quoteRequest->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full h-10 inline-flex items-center justify-center text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm focus:outline-none">
                                👥 Converteste / Asociază cu Client
                            </button>
                        </form>
                        <span class="text-[8px] text-slate-400 leading-normal block">
                            *Va verifica automat baza de date după <strong>email</strong> sau <strong>telefon</strong> pentru a preveni inregistrarile duplicate din CRM.
                        </span>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Chronological Unified CRM Timeline Feed -->
        <div class="space-y-6 lg:col-span-2">
            
            <!-- Timeline details and logs container -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5 space-y-6">
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Timeline Istoric CRM</h3>
                    <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Alocări administrative, modificări de stadiu și notițe interne</span>
                </div>

                <!-- Internal Note taking form -->
                <form action="{{ route('admin.quotes.note', $quoteRequest->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label for="note" class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Adaugă notă internă</label>
                        <textarea name="note" id="note" rows="3" placeholder="Scrie detalii despre discuția cu clientul sau planificarea lucrării..." class="w-full p-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all placeholder:text-slate-400 font-semibold leading-relaxed" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-5 h-9.5 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md focus:outline-none">
                            💾 Salvează notă timeline
                        </button>
                    </div>
                </form>

                <!-- Chronological Timeline list -->
                <div class="border-t border-slate-50 pt-6">
                    <div class="relative pl-6 border-l-2 border-slate-100 space-y-6">
                        @forelse($timelineNotes as $note)
                            <div class="relative">
                                <!-- Timeline icon nodes -->
                                <span class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-white border-2 flex items-center justify-center text-[8px]
                                    @if($note->type === 'note')
                                        border-emerald-500 text-emerald-850 bg-emerald-50
                                    @elseif($note->type === 'status_change')
                                        border-blue-500 text-blue-850 bg-blue-50
                                    @elseif($note->type === 'assignment_change')
                                        border-purple-500 text-purple-850 bg-purple-50
                                    @elseif($note->type === 'priority_change')
                                        border-amber-500 text-amber-850 bg-amber-50
                                    @elseif($note->type === 'client_link')
                                        border-teal-500 text-teal-850 bg-teal-50
                                    @elseif($note->type === 'created')
                                        border-slate-500 text-slate-850 bg-slate-50
                                    @else
                                        border-red-500 text-red-850 bg-red-50
                                    @endif
                                " title="{{ ucfirst($note->type) }}">
                                    @if($note->type === 'note') 📝 
                                    @elseif($note->type === 'status_change') 🔄 
                                    @elseif($note->type === 'assignment_change') 👤 
                                    @elseif($note->type === 'priority_change') ⭐ 
                                    @elseif($note->type === 'client_link') 👥 
                                    @elseif($note->type === 'created') 🆕 
                                    @else ⚠️ @endif
                                </span>

                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-extrabold text-slate-800">
                                            {{ $note->user ? $note->user->name : 'Sistem Automat' }}
                                        </span>
                                        <span class="text-[8px] text-slate-400 font-bold">
                                            {{ $note->created_at->diffForHumans() }} ({{ $note->created_at->format('d.m.Y H:i') }})
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 bg-slate-50/40 border border-slate-200/20 p-3 rounded-2xl font-semibold leading-relaxed">
                                        {{ $note->note }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400 font-medium">
                                Nu există activități înregistrate pe timeline.
                            </div>
                        @endforelse
                    </div>

                    <!-- Timeline pagination links -->
                    <div class="mt-6 pt-4 border-t border-slate-50">
                        {{ $timelineNotes->links() }}
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
