@extends('public.layouts.app')

@section('title', 'Services')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <header class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Our Services</h1>
            <p class="mt-2 text-slate-600">Everything we offer.</p>
        </header>

        @if ($services->isEmpty())
            <p class="text-slate-600">No services yet.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="block rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-[var(--brand)] hover:shadow-md">
                        @if ($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="mb-4 h-40 w-full rounded-lg object-cover">
                        @endif
                        <h3 class="text-lg font-semibold text-slate-900">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ str($service->description)->limit(140) }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
