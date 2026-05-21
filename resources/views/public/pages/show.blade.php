@extends('public.layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? str($page->excerpt)->limit(160))

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $page->title }}</h1>

        @if ($page->featured_image)
            <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="mt-8 w-full rounded-2xl object-cover">
        @endif

        <div class="prose prose-slate mt-8 max-w-none">
            {!! nl2br(e($page->content)) !!}
        </div>
    </article>
@endsection
