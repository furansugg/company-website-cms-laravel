@extends('public.layouts.app')

@section('title', 'Blog')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <header class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Blog</h1>
            <p class="mt-2 text-slate-600">Latest articles and news.</p>
        </header>

        <form method="get" class="mb-8 flex flex-wrap items-center gap-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search articles..."
                   class="w-full max-w-xs rounded-md border border-slate-300 px-3 py-2 text-sm">
            <select name="category" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-md bg-[var(--brand)] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Filter</button>
        </form>

        @if ($articles->isEmpty())
            <p class="text-slate-600">No articles found.</p>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="group block overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:shadow-md">
                        @if ($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="h-44 w-full object-cover">
                        @endif
                        <div class="p-5">
                            @if ($article->category)
                                <p class="text-xs font-medium uppercase tracking-wide text-[var(--brand)]">{{ $article->category->name }}</p>
                            @endif
                            <h3 class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-[var(--brand)]">{{ $article->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ str($article->excerpt)->limit(140) }}</p>
                            <p class="mt-3 text-xs text-slate-500">{{ optional($article->published_at)->format('M d, Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
    </section>
@endsection
