@extends('layouts.admin')

@section('title', 'Tablou de Bord')
@section('section_title', 'Prezentare Generală')

@section('content')
<div class="space-y-8">

    <!-- Top alert warning credentials check -->
    <div class="p-4 bg-emerald-50/50 border border-emerald-100/50 rounded-2xl flex items-start gap-3">
        <span class="text-lg">🛡️</span>
        <div class="flex flex-col">
            <span class="text-xs font-bold text-slate-800">Securitate Panou Control</span>
            <span class="text-[10px] text-slate-500 leading-relaxed mt-1">
                Pentru siguranța datelor Optera Vision, vă recomandăm să schimbați parola implicită generată la instalare imediat din setările contului dumneavoastră.
            </span>
        </div>
    </div>

    <!-- Quick stats grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Lead CRM card -->
        <div class="bg-white border border-slate-100/80 rounded-2xl p-6 shadow-sm shadow-slate-100/10 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Cereri Oferte (CRM)</span>
                <span class="text-3xl font-extrabold text-slate-900 leading-none mt-4">{{ $metrics['leads_count'] }}</span>
                <span class="text-[10px] text-slate-500 font-semibold leading-none mt-2">Leads primite pe site</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-lg border border-slate-100">
                📞
            </div>
        </div>

        <!-- Active Services card -->
        <div class="bg-white border border-slate-100/80 rounded-2xl p-6 shadow-sm shadow-slate-100/10 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Servicii Supraveghere</span>
                <span class="text-3xl font-extrabold text-slate-900 leading-none mt-4">{{ $metrics['services_count'] }}</span>
                <span class="text-[10px] text-slate-500 font-semibold leading-none mt-2">Instalate activ din DB</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-lg border border-slate-100">
                🛠️
            </div>
        </div>

        <!-- Portfolio Projects card -->
        <div class="bg-white border border-slate-100/80 rounded-2xl p-6 shadow-sm shadow-slate-100/10 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Proiecte Portofoliu</span>
                <span class="text-3xl font-extrabold text-slate-900 leading-none mt-4">{{ $metrics['projects_count'] }}</span>
                <span class="text-[10px] text-slate-500 font-semibold leading-none mt-2">Lucrări publicate în portofoliu</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-lg border border-slate-100">
                📂
            </div>
        </div>
    </div>

    <!-- Audit logs and CRM lists -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Audit activity logs -->
        <div class="bg-white border border-slate-100/80 rounded-2xl shadow-sm shadow-slate-100/10 p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div class="flex flex-col">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Jurnal Activități Administrative</h3>
                    <span class="text-[10px] text-slate-400 font-medium leading-none mt-1.5">Ultimele 5 acțiuni de audit efectuate în sistem</span>
                </div>
                <span class="text-[10px] font-extrabold text-slate-500 bg-slate-50 border border-slate-200/50 px-2 py-1 rounded-lg">Audit securitate</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3 pl-2">Utilizator</th>
                            <th class="pb-3">Acțiune</th>
                            <th class="pb-3">Detalii Audit</th>
                            <th class="pb-3">Adresă IP</th>
                            <th class="pb-3 text-right pr-2">Dată audit</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold divide-y divide-slate-50 text-slate-700">
                        @forelse($metrics['recent_logs'] as $log)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="py-3 pl-2 text-slate-900 font-bold">
                                    {{ $log->user ? $log->user->name : 'Sistem Automat' }}
                                </td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase
                                        @if(str_contains($log->action, 'failed') || str_contains($log->action, 'block'))
                                            bg-red-50 text-red-850
                                        @elseif(str_contains($log->action, 'success') || str_contains($log->action, 'login'))
                                            bg-emerald-50 text-emerald-850
                                        @else
                                            bg-slate-50 text-slate-650
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-500 font-medium max-w-xs truncate">{{ $log->description }}</td>
                                <td class="py-3 font-mono text-[10px] text-slate-400">{{ $log->ip_address }}</td>
                                <td class="py-3 text-right pr-2 text-slate-450 font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                    Nu s-au înregistrat activități administrative recente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
