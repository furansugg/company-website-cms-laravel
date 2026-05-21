@extends('public.layouts.app')

@section('title', $service->name)
@section('meta_description', str($service->description)->limit(160))

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $service->name }}</h1>

        @if ($service->image)
            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="mt-8 w-full rounded-2xl object-cover">
        @endif

        <div class="prose prose-slate mt-8 max-w-none">
            {!! nl2br(e($service->description)) !!}
        </div>

        <div class="mt-10">
            <a href="{{ route('contact') }}" class="inline-flex items-center rounded-md bg-[var(--brand)] px-5 py-3 text-sm font-semibold text-white shadow hover:opacity-90">
                Get in touch
            </a>
        </div>
    </article>
@endsection
