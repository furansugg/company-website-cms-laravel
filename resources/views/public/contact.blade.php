@extends('public.layouts.app')

@section('title', 'Contact Us')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <header class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Contact us</h1>
            <p class="mt-2 text-slate-600">Have a question? Send us a message and we will get back to you.</p>
        </header>

        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ route('contact.store') }}" class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[var(--brand)] focus:ring-[var(--brand)]">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                           class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[var(--brand)] focus:ring-[var(--brand)]">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone (optional)</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                           class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[var(--brand)] focus:ring-[var(--brand)]">
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-700">Subject</label>
                    <input id="subject" name="subject" type="text" value="{{ old('subject') }}"
                           class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[var(--brand)] focus:ring-[var(--brand)]">
                </div>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                <textarea id="message" name="message" rows="6" required
                          class="mt-1 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[var(--brand)] focus:ring-[var(--brand)]">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="inline-flex items-center rounded-md bg-[var(--brand)] px-5 py-3 text-sm font-semibold text-white shadow hover:opacity-90">
                Send message
            </button>
        </form>
    </section>
@endsection
