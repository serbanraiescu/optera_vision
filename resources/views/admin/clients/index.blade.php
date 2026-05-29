@extends('layouts.admin')

@section('title', 'Registru Clienți')
@section('section_title', 'Registru Clienți')

@section('content')
<div class="space-y-6">

    <!-- Header bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
        <div class="flex flex-col">
            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider leading-none">Registru Clienți Optera</h3>
            <span class="text-[10px] text-slate-400 font-semibold mt-2 leading-none">Baza de date cu toți clienții înregistrați și asociați în CRM</span>
        </div>
    </div>

    <!-- Filter/Search Bar -->
    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm shadow-slate-100/5">
        <form action="{{ route('admin.clients') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex-grow flex flex-col gap-1.5">
                <label for="search" class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Caută în Registru</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Caută după nume, telefon, email sau localitate..." class="h-10 px-3.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-emerald-800 focus:border-emerald-800 focus:outline-none transition-all placeholder:text-slate-400 font-semibold">
            </div>
            <div class="flex sm:self-end gap-2">
                <a href="{{ route('admin.clients') }}" class="inline-flex items-center justify-center px-4 h-9.5 text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:text-slate-800 rounded-lg transition-all focus:outline-none">
                    Resetează
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-5 h-9.5 text-xs font-bold text-white bg-emerald-800 hover:bg-emerald-950 rounded-lg transition-all shadow-md focus:outline-none">
                    🔍 Caută
                </button>
            </div>
        </form>
    </div>

    <!-- Grid List Table of Clients -->
    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm shadow-slate-100/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-extrabold text-slate-450 uppercase tracking-wider">
                        <th class="py-4 pl-6">Nume Client</th>
                        <th class="py-4">Telefon</th>
                        <th class="py-4">Email</th>
                        <th class="py-4">Localitate</th>
                        <th class="py-4">Sursă Origine</th>
                        <th class="py-4">Notă Înregistrare</th>
                        <th class="py-4 text-right pr-6">Data Înregistrării</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-semibold divide-y divide-slate-50 text-slate-700">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <!-- Client Name -->
                            <td class="py-4 pl-6 text-slate-900 font-bold">
                                👥 {{ $client->name }}
                            </td>
                            <!-- Phone -->
                            <td class="py-4">
                                <a href="tel:{{ $client->phone }}" class="text-emerald-850 hover:underline">
                                    {{ $client->phone }}
                                </a>
                            </td>
                            <!-- Email -->
                            <td class="py-4 font-medium text-slate-600">
                                {{ $client->email }}
                            </td>
                            <!-- Locality -->
                            <td class="py-4 text-slate-650">
                                {{ $client->locality ?: '-' }}
                            </td>
                            <!-- Source -->
                            <td class="py-4 capitalize font-semibold text-slate-500">
                                <span class="bg-slate-100 text-slate-550 border border-slate-200/50 px-2 py-0.5 rounded-lg text-[9px] uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $client->source ?: 'direct') }}
                                </span>
                            </td>
                            <!-- Notes -->
                            <td class="py-4 text-slate-450 font-medium max-w-xs truncate" title="{{ $client->notes }}">
                                {{ $client->notes ?: '-' }}
                            </td>
                            <!-- Created Date -->
                            <td class="py-4 text-right pr-6 text-slate-400 font-medium">
                                {{ $client->created_at->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-450 font-medium bg-slate-50/10">
                                Nu există clienți înregistrați în bază care să corespundă criteriilor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100 bg-slate-50/10">
            {{ $clients->links() }}
        </div>
    </div>

</div>
@endsection
