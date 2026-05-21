<header class="border-b border-slate-200 bg-white sticky top-0 z-40" x-data="{ open: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if ($siteSettings->logo)
                <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->site_name }}" class="h-8 w-auto">
            @endif
            <span class="text-lg font-semibold text-slate-900">{{ $siteSettings->site_name }}</span>
        </a>

        <nav class="hidden items-center gap-6 md:flex">
            @foreach ($headerMenus as $menu)
                <a href="{{ $menu->url }}"
                   class="text-sm font-medium text-slate-700 hover:text-[var(--brand)]"
                   @if ($menu->open_in_new_tab) target="_blank" rel="noopener" @endif>
                    {{ $menu->label }}
                </a>
            @endforeach
        </nav>

        <button
            class="inline-flex items-center justify-center rounded-md p-2 text-slate-700 md:hidden"
            type="button"
            @click="open = !open"
            aria-label="Toggle menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div x-show="open" x-collapse class="border-t border-slate-200 md:hidden">
        <div class="flex flex-col px-4 py-3 sm:px-6">
            @foreach ($headerMenus as $menu)
                <a href="{{ $menu->url }}" class="py-2 text-sm font-medium text-slate-700 hover:text-[var(--brand)]">
                    {{ $menu->label }}
                </a>
            @endforeach
        </div>
    </div>
</header>
