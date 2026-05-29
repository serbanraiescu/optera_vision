@extends('layouts.admin')

@section('title', 'Tablou de Bord')
@section('section_title', 'Prezentare Generală')

@section('content')
<div class="space-y-8 animate-fade-in">

    <!-- Top welcome bar -->
    <div class="p-6 bg-gradient-to-r from-emerald-800/10 via-emerald-800/5 to-transparent border border-emerald-800/10 rounded-3xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-4">
            <span class="text-3xl">👋</span>
            <div class="flex flex-col">
                <h3 class="text-base font-extrabold text-slate-800 leading-none">Salut, {{ auth()->user()->name }}!</h3>
                <p class="text-xs text-slate-500 mt-2 font-medium">Bine ai venit în Panoul Administrare & CRM pentru Optera Vision.</p>
            </div>
        </div>
        @if(auth()->user()->role !== 'technician')
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quotes.export') }}" class="inline-flex items-center justify-center px-4.5 h-10 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm focus:outline-none" title="Descarcă baza de date în format excel CSV">
                📥 Exportă Date (CSV)
            </a>
            <a href="{{ route('admin.quotes') }}" class="inline-flex items-center justify-center px-4.5 h-10 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-xl transition-all shadow-md shadow-emerald-900/10 focus:outline-none">
                📞 Gestionează CRM
            </a>
        </div>
        @endif
    </div>

    <!-- Active CRM Stats Widgets -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- New Leads -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Noi</span>
                <span class="text-xl">🆕</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none">{{ $metrics['new_quotes'] }}</span>
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">solicitări</span>
            </div>
            <div class="text-[10px] text-slate-405 mt-2 font-semibold flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
  Așteaptă preluare
            </div>
            <div class="absolute right-0 bottom-0 translate-x-2 translate-y-2 opacity-5 text-4xl group-hover:scale-110 transition-transform select-none">🆕</div>
        </div>

        <!-- Contacted Leads -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Contactate</span>
                <span class="text-xl">📞</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none">{{ $metrics['contacted_quotes'] }}</span>
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">solicitări</span>
            </div>
            <div class="text-[10px] text-slate-405 mt-2 font-semibold">
                În proces de evaluare
            </div>
            <div class="absolute right-0 bottom-0 translate-x-2 translate-y-2 opacity-5 text-4xl group-hover:scale-110 transition-transform select-none">📞</div>
        </div>

        <!-- Accepted Leads -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-emerald-750 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Acceptate</span>
                <span class="text-xl">✅</span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-none">{{ $metrics['accepted_quotes'] }}</span>
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">lucrări</span>
            </div>
            <div class="text-[10px] text-slate-405 mt-2 font-semibold">
                Semnate și validate
            </div>
            <div class="absolute right-0 bottom-0 translate-x-2 translate-y-2 opacity-5 text-4xl group-hover:scale-110 transition-transform select-none">✅</div>
        </div>

        <!-- Active estimated projects dynamic value -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Valoare Activă</span>
                <span class="text-xl">💰</span>
            </div>
            <div class="mt-4 flex items-baseline gap-1">
                <span class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-none">{{ number_format($metrics['active_project_value'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider">RON</span>
            </div>
            <div class="text-[10px] text-slate-405 mt-2.5 font-semibold">
                Estimat contracte în lucru
            </div>
            <div class="absolute right-0 bottom-0 translate-x-2 translate-y-2 opacity-5 text-4xl group-hover:scale-110 transition-transform select-none">💰</div>
        </div>
    </div>

    <!-- Second row widgets -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm text-center">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Total Cereri</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-2">{{ $metrics['total_quotes'] }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm text-center">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Cereri Pierdute</span>
            <span class="block text-2xl font-extrabold text-red-750 mt-2">{{ $metrics['lost_quotes'] }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm text-center">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Servicii Portofoliu</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-2">{{ $metrics['services_count'] }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm text-center">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Lucrări Publicate</span>
            <span class="block text-2xl font-extrabold text-slate-800 mt-2">{{ $metrics['projects_count'] }}</span>
        </div>
    </div>

    <!-- Main Content splits: Recent leads and Audit security -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Left 2 Cols: Recent leads board -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5 xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div class="flex flex-col">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Cereri Recente de Ofertă</h3>
                    <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Ultimele 5 solicitări înregistrate de clienți</span>
                </div>
                <a href="{{ route('admin.quotes') }}" class="text-[10px] font-extrabold text-emerald-800 hover:text-emerald-950 flex items-center gap-1 transition-colors">
                    Vezi toate CRM →
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentQuotes as $quote)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-slate-100 rounded-2xl hover:bg-slate-50/30 transition-colors gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-sm font-bold border border-slate-200/40 text-slate-700">
                                {{ substr($quote->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <a href="{{ route('admin.quotes.show', $quote->id) }}" class="text-xs font-bold text-slate-800 hover:text-emerald-850 transition-colors">
                                    {{ $quote->name }}
                                </a>
                                <span class="text-[10px] text-slate-400 mt-1 font-semibold">
                                    {{ $quote->phone }} • {{ $quote->locality ?: 'Fără localitate' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            <div class="flex flex-col items-end hidden sm:flex">
                                <span class="text-[10px] font-bold text-slate-700">{{ $quote->camera_count }} camere</span>
                                <span class="text-[9px] text-slate-400 font-semibold mt-1">{{ number_format($quote->estimated_value, 0, ',', '.') }} RON</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase border {{ $quote->status->colorClass() }}">
                                {{ $quote->status->label() }}
                            </span>
                            <a href="{{ route('admin.quotes.show', $quote->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 flex items-center justify-center text-slate-450 border border-slate-200/40 transition-colors" title="Deschide CRM">
                                👁️
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400 font-medium border border-dashed border-slate-200 rounded-2xl">
                        Nu s-au înregistrat solicitări de oferte recente.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right 1 Col: Audit Security logs -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div class="flex flex-col">
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider leading-none">Audit Securitate & CRM</h3>
                    <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Log-uri administrative recente</span>
                </div>
                <span class="text-[8px] font-bold text-slate-400 bg-slate-50 border border-slate-200/30 px-2 py-0.5 rounded">Real-time</span>
            </div>

            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
                @forelse($recentLogs as $log)
                    <div class="p-3 bg-slate-50/50 border border-slate-200/20 rounded-xl flex flex-col gap-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-extrabold text-slate-700 truncate max-w-[120px]">
                                👤 {{ $log->user ? $log->user->name : 'Sistem Automat' }}
                            </span>
                            <span class="text-[8px] text-slate-400 font-bold leading-none">
                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase tracking-wide inline-flex mr-auto px-1.5 py-0.5 rounded {{ str_contains($log->action, 'failed') || str_contains($log->action, 'blocked') ? 'bg-red-50 text-red-800' : 'bg-emerald-50 text-emerald-800' }}">
                            {{ str_replace('_', ' ', $log->action) }}
                        </span>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed mt-0.5">{{ $log->description }}</p>
                        <span class="text-[8px] font-mono text-slate-400 block mt-1">IP: {{ $log->ip_address }}</span>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-450 font-medium">
                        Nu sunt log-uri de securitate recente.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
