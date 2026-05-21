<footer class="mt-20 border-t border-slate-200 bg-slate-50">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
            <h3 class="text-base font-semibold text-slate-900">{{ $companyProfile->name }}</h3>
            @if ($companyProfile->about)
                <p class="mt-2 text-sm text-slate-600">{{ str($companyProfile->about)->limit(160) }}</p>
            @endif
        </div>

        <div>
            <h3 class="text-base font-semibold text-slate-900">Links</h3>
            <ul class="mt-2 space-y-1">
                @foreach ($footerMenus as $menu)
                    <li>
                        <a href="{{ $menu->url }}" class="text-sm text-slate-600 hover:text-[var(--brand)]">{{ $menu->label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-base font-semibold text-slate-900">Contact</h3>
            <ul class="mt-2 space-y-1 text-sm text-slate-600">
                @if ($companyProfile->email)
                    <li>Email: <a href="mailto:{{ $companyProfile->email }}" class="hover:text-[var(--brand)]">{{ $companyProfile->email }}</a></li>
                @endif
                @if ($companyProfile->phone)
                    <li>Phone: {{ $companyProfile->phone }}</li>
                @endif
                @if ($companyProfile->address)
                    <li>{{ $companyProfile->address }}</li>
                @endif
            </ul>

            @if (! empty($companyProfile->social_media))
                <div class="mt-3 flex gap-3 text-sm">
                    @foreach ($companyProfile->social_media as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="text-slate-600 hover:text-[var(--brand)]">
                            {{ ucfirst($platform) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-slate-200 px-4 py-6 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
        &copy; {{ now()->year }} {{ $companyProfile->name }}. All rights reserved.
    </div>
</footer>
