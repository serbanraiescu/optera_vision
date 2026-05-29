@extends('layouts.public')

@section('title', $page->meta_title ?? setting('seo.default_title'))
@section('meta_description', $page->meta_description ?? setting('seo.default_description'))

@section('content')
    <!-- Dynamic page sections rendering -->
    @foreach($page->sections as $section)
        @includeIf('public.sections.' . $section->section_key, ['blocks' => $section->blocks])
    @endforeach
@endsection
