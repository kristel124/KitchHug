<div class="mx-auto grid max-w-7xl items-start gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[16rem_minmax(0,1fr)] lg:px-8 lg:py-10">

    {{-- Sidebar --}}
    <aside class="space-y-5 lg:sticky lg:top-28">
        <div class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">

            {{-- User identity --}}
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-orange-100 bg-slate-900 text-sm font-black text-white">
                    @auth
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                        @else
                            {{ Auth::user()->initials() }}
                        @endif
                    @endauth
                </div>
                <div class="min-w-0">
                    @auth
                        <p class="truncate text-sm font-black text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    @endauth
                </div>
            </div>

            {{-- Nav --}}
            <nav aria-label="{{ __('Account settings') }}" class="mt-5 space-y-1 border-t border-orange-100 pt-4 dark:border-slate-700">
                <a href="{{ route('profile.edit') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-orange-50 font-bold text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-700 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                    <svg viewBox="0 0 24 24" fill="none" class="size-4 shrink-0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ __('Profile') }}
                </a>
                <a href="{{ route('appearance.edit') }}" wire:navigate
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs('appearance.edit') ? 'bg-orange-50 font-bold text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-700 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                    <svg viewBox="0 0 24 24" fill="none" class="size-4 shrink-0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    {{ __('Appearance') }}
                </a>
            </nav>
        </div>

    </aside>

    {{-- Content --}}
    <div class="min-w-0 space-y-6">
        <div class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-7">
            <div class="mb-6">
                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ $heading ?? '' }}</h2>
                @if($subheading ?? '')
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subheading }}</p>
                @endif
            </div>
            <div class="w-full max-w-lg">
                {{ $slot }}
            </div>
        </div>
    </div>

</div>
