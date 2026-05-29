@extends('layouts.admin')

@section('title', 'Servicii Supraveghere')
@section('section_title', 'Administrare Servicii')

@section('content')
<div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm flex flex-col items-center justify-center text-center space-y-4 min-h-[400px]">
    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-3xl">
        🛠️
    </div>
    <div class="space-y-2 max-w-md">
        <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Catalog Servicii</h3>
        <p class="text-xs text-slate-450 leading-relaxed font-semibold">
            Modul în curs de dezvoltare (Planificat pentru Faza 5). Aici vei putea adăuga, edita și dezactiva serviciile de supraveghere video afișate pe site-ul public.
        </p>
    </div>
    <div class="pt-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold bg-slate-100 text-slate-500 uppercase tracking-widest">
            ⏳ Coming Soon — Faza 5
        </span>
    </div>
</div>
@endsection
