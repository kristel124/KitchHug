<header class="sticky top-0 z-50 w-full border-b border-orange-100/60 bg-white/90 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3 rounded-2xl p-1 focus:outline-none focus:ring-2 focus:ring-orange-500">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500 text-xl text-white shadow-md shadow-orange-500/20 transition-all duration-300 group-hover:scale-105 group-hover:bg-orange-600">
                    <span role="img" aria-label="Cooking pot">🍳</span>
                </div>
                <span class="leading-none">
                    <span class="block text-2xl font-black tracking-tight text-slate-900">Kitch<span class="text-orange-500">Hug</span></span>
                    <span class="mt-1 block text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Made with love</span>
                </span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold md:flex" aria-label="Main navigation">
                <a href="{{ route('home') }}#explore" class="text-slate-600 transition-colors hover:text-orange-500">Explore Recipes</a>
                <details class="group relative">
                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-slate-600 transition-colors hover:text-orange-500 [&::-webkit-details-marker]:hidden">
                        Categories
                        <svg class="h-3.5 w-3.5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="absolute left-0 top-full z-50 mt-2 max-h-80 w-56 overflow-y-auto rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">
                        @foreach ([
                            'chicken' => 'Chicken Recipes',
                            'seafood' => 'Seafood Recipes',
                            'beef' => 'Beef Recipes',
                            'pasta' => 'Pasta Recipes',
                            'dips-spreads' => 'Dips & Spreads',
                            'dessert' => 'Dessert',
                            'pork' => 'Pork Recipes',
                            'side-dish' => 'Side Dish',
                        ] as $slug => $label)
                            <a href="{{ route('recipes.public-category', $slug) }}" class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </details>
                <a href="{{ route('home') }}#about" class="text-slate-600 transition-colors hover:text-orange-500">About Us</a>
            </nav>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 transition-all hover:bg-orange-100/50 hover:text-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 sm:px-5 sm:text-sm">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="rounded-xl bg-orange-500 px-3 py-2.5 text-xs font-bold text-white shadow-sm shadow-orange-500/30 transition-all hover:bg-orange-600 hover:shadow-md hover:shadow-orange-500/40 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:px-5 sm:text-sm">
                    Get Started
                </a>
            </div>
        </div>
    </div>
</header>
