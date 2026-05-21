@extends('public.layouts.app')

@section('content')
    @php($banner = $banners->first())

    <section class="bg-gradient-to-br from-slate-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                        {{ $banner?->title ?? 'Welcome to ' . $siteSettings->site_name }}
                    </h1>
                    @if ($banner?->subtitle)
                        <p class="mt-4 text-lg text-slate-600">{{ $banner->subtitle }}</p>
                    @else
                        <p class="mt-4 text-lg text-slate-600">{{ $siteSettings->site_description }}</p>
                    @endif

                    @if ($banner?->cta_label && $banner?->cta_url)
                        <a href="{{ $banner->cta_url }}"
                           class="mt-6 inline-flex items-center rounded-md bg-[var(--brand)] px-5 py-3 text-sm font-semibold text-white shadow hover:opacity-90">
                            {{ $banner->cta_label }}
                        </a>
                    @endif
                </div>

                @if ($banner?->image)
                    <div class="overflow-hidden rounded-2xl shadow-lg">
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($services->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Our Services</h2>
                    <p class="mt-2 text-slate-600">What we can do for your business.</p>
                </div>
                <a href="{{ route('services.index') }}" class="text-sm font-semibold text-[var(--brand)]">View all &rarr;</a>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="block rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-[var(--brand)] hover:shadow-md">
                        @if ($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="mb-4 h-32 w-full rounded-lg object-cover">
                        @endif
                        <h3 class="text-lg font-semibold text-slate-900">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ str($service->description)->limit(120) }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($articles->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Latest Articles</h2>
                    <p class="mt-2 text-slate-600">Stories and updates from our team.</p>
                </div>
                <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-[var(--brand)]">All articles &rarr;</a>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
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
        </section>
    @endif
@endsection
