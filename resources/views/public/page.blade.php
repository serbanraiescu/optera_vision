@extends('layouts.public')

@section('title', ($page->meta_title ?? $page->title) . ' | Optera Vision')
@section('meta_description', $page->meta_description ?? setting('seo.default_description'))

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white py-16 px-6 md:px-16 min-h-[60vh]">
    <div class="max-w-[800px] mx-auto w-full">
        <!-- Breadcrumbs / Back button -->
        <div class="mb-8">
            <a href="{{ url('/') }}" class="text-[10px] font-extrabold text-[#545f73] hover:text-[#022717] transition-colors uppercase tracking-wider flex items-center gap-1.5">
                &larr; Înapoi la acasă
            </a>
        </div>

        <!-- Dynamic legal policy article card -->
        <article class="bg-white border border-gray-150 rounded-3xl p-8 md:p-12 shadow-sm space-y-8">
            <div class="space-y-4 border-b border-slate-100 pb-6 text-left">
                <span class="inline-block py-1.5 px-3 bg-emerald-50 text-[#022717] rounded-full font-bold text-[9px] tracking-wider uppercase">
                    Document Oficial CMS
                </span>
                <h1 class="font-extrabold text-3xl md:text-4xl text-[#022717] tracking-tight">
                    {{ $page->title }}
                </h1>
                <p class="text-[10px] text-slate-400 font-semibold">
                    Ultima actualizare: {{ $page->updated_at->format('d.m.Y') }}
                </p>
            </div>

            <!-- Page rich content area -->
            <div class="prose prose-slate max-w-none text-left font-sans text-xs md:text-sm text-slate-650 leading-relaxed space-y-6">
                {!! $page->content !!}
            </div>
        </article>
    </div>
</div>
@endsection
