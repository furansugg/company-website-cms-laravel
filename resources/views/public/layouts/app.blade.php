<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettings->default_meta_title ?? $siteSettings->site_name)</title>
    <meta name="description" content="@yield('meta_description', $siteSettings->default_meta_description)">

    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @elseif ($siteSettings->default_og_image)
        <meta property="og:image" content="{{ asset('storage/' . $siteSettings->default_og_image) }}">
    @endif

    @if ($siteSettings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --brand: {{ $siteSettings->primary_color ?? '#2563eb' }}; }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased font-sans">
    @include('public.partials.header')

    <main>
        @yield('content')
    </main>

    @include('public.partials.footer')
</body>
</html>
