<footer class="border-t border-orange-100 bg-white dark:border-slate-700 dark:bg-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}">
                <img src="{{ asset('KitchHug_Logo.png') }}" alt="KitchHug — Your Recipe Box" class="h-12 w-auto object-contain">
            </a>
            <p class="text-center text-xs font-medium text-slate-400 dark:text-slate-500">© {{ date('Y') }} KitchHug. Made with ❤️ for home cooks.</p>
        </div>
    </div>
</footer>
