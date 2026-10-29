<section class="relative isolate overflow-hidden border-b border-orange-100 bg-gradient-to-r from-[#faf7f2] via-[#fffaf0] to-[#fff0dc]">
    <div class="pointer-events-none absolute -right-16 -top-20 -z-10 h-72 w-72 rounded-full bg-orange-200/40 blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 rounded-full border border-orange-200/80 bg-white/70 px-4 py-2 text-sm font-bold text-orange-700 shadow-sm transition hover:bg-white">
            <span aria-hidden="true">←</span> {{ __('Back to profile') }}
        </a>
        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-600">{{ __('Your account') }}</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ __('Settings') }}</h1>
        <p class="mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">{{ __('Manage your profile and account settings') }}</p>
    </div>
</section>
