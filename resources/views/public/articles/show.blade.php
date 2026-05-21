@extends('public.layouts.app')

@section('title', $article->meta_title ?? $article->title)
@section('meta_description', $article->meta_description ?? str($article->excerpt)->limit(160))

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($article->category)
            <p class="text-sm font-medium uppercase tracking-wide text-[var(--brand)]">{{ $article->category->name }}</p>
        @endif
        <h1 class="mt-2 text-4xl font-bold tracking-tight text-slate-900">{{ $article->title }}</h1>
        <p class="mt-2 text-sm text-slate-500">
            @if ($article->author)
                By {{ $article->author->name }} ·
            @endif
            {{ optional($article->published_at)->format('M d, Y') }}
        </p>

        @if ($article->featured_image)
            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="mt-8 w-full rounded-2xl object-cover">
        @endif

        <div class="prose prose-slate mt-8 max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>

        @if ($article->tags->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($article->tags as $tag)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </article>

    @if ($related->isNotEmpty())
        <section class="mx-auto max-w-7xl border-t border-slate-200 px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Related articles</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                @foreach ($related as $item)
                    <a href="{{ route('articles.show', $item->slug) }}" class="block rounded-2xl border border-slate-200 p-5 hover:border-[var(--brand)]">
                        <h3 class="text-base font-semibold text-slate-900">{{ $item->title }}</h3>
                        <p class="mt-1 text-xs text-slate-500">{{ optional($item->published_at)->format('M d, Y') }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
