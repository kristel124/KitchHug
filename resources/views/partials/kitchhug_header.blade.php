<header class="sticky top-0 z-50 border-b border-orange-100 bg-white/95 shadow-sm backdrop-blur-md">

    @php
        $recipeCategories = [
            'chicken' => 'Chicken Recipes',
            'seafood' => 'Seafood Recipes',
            'beef' => 'Beef Recipes',
            'pasta' => 'Pasta Recipes',
            'dips-spreads' => 'Dips & Spreads',
            'dessert' => 'Dessert',
            'pork' => 'Pork Recipes',
            'side-dish' => 'Side Dish',
        ];

        $courseCategories = [
            'Breakfast',
            'Lunch',
            'Dinner',
            'Appetizers',
            'Dessert',
        ];

        $methodCategories = [
            'baking' => 'Baking',
            'grilling' => 'Grilling',
            'frying' => 'Frying',
            'boiling' => 'Boiling',
            'steaming' => 'Steaming',
            'roasting' => 'Roasting',
        ];
    @endphp

    <div class="mx-auto max-w-[1440px] px-6">
        <div class="flex min-h-[76px] items-center gap-6 py-3">

            {{-- LOGO --}}
            <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}"
               class="group flex shrink-0 items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-orange-600 to-orange-400 text-xl shadow-md shadow-orange-500/20 transition-transform group-hover:scale-105">
                    🍳
                </div>

                <div>
                    <div class="text-2xl font-black tracking-tight text-slate-900">
                        Kitch<span class="text-orange-500">Hug</span>
                    </div>

                    <div class="text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400">
                        Your Recipe Box
                    </div>
                </div>
            </a>

            {{-- DESKTOP NAVIGATION --}}
            <nav class="flex shrink-0 items-center gap-5 text-sm font-semibold">

                {{-- HOME --}}
                <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}"
                   class="relative py-2 transition-colors hover:text-orange-500
                   {{ request()->routeIs('user_homepage') ? 'text-orange-500' : 'text-slate-600' }}">

                    Home

                    @if(request()->routeIs('user_homepage'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full bg-orange-500"></span>
                    @endif
                </a>

                {{-- RECIPES DROPDOWN --}}
                <details class="group relative">

                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-slate-600 transition-colors hover:text-orange-500 [&::-webkit-details-marker]:hidden">
                        Recipes

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="h-3.5 w-3.5 shrink-0 transition-transform group-open:rotate-180"
                             aria-hidden="true">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </summary>

                    <div class="absolute left-0 top-full z-50 mt-2 max-h-80 w-56 overflow-y-auto rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">

                        @foreach($recipeCategories as $slug => $label)
                            <a href="{{ auth()->check() ? route('recipes.category', $slug) : route('recipes.public-category', $slug) }}"
                               class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500">

                                {{ $label }}

                            </a>
                        @endforeach

                    </div>
                </details>

                {{-- MORE DROPDOWN --}}
                <details class="group relative">

                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-slate-600 transition-colors hover:text-orange-500 [&::-webkit-details-marker]:hidden">

                        More

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="h-3.5 w-3.5 shrink-0 transition-transform group-open:rotate-180"
                             aria-hidden="true">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>

                    </summary>

                    <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">

                        {{-- COOKING TIPS --}}
                        <a href="#"
                           class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500">

                            Cooking Tips

                        </a>

                        {{-- BY METHODS --}}
                        <details class="group/method relative">

                            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 [&::-webkit-details-marker]:hidden">

                                By Methods

                                <span class="text-lg leading-none" aria-hidden="true">›</span>

                            </summary>

                            <div class="absolute left-full top-0 z-50 ml-1 w-48 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">

                                @foreach($methodCategories as $slug => $label)
                                    <a href="#"
                                       class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500">

                                        {{ $label }}

                                    </a>
                                @endforeach

                            </div>
                        </details>

                        {{-- COURSE SUBMENU --}}
                        <details class="group/course relative">

                            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 [&::-webkit-details-marker]:hidden">

                                Course

                                <span class="text-lg leading-none" aria-hidden="true">›</span>

                            </summary>

                            <div class="absolute left-full top-0 z-50 ml-1 w-44 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">

                                @foreach($courseCategories as $category)
                                    <a href="#"
                                       class="block rounded-lg px-3 py-2 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500">

                                        {{ $category }}

                                    </a>
                                @endforeach

                            </div>
                        </details>

                    </div>
                </details>

            </nav>

            {{-- COMPACT SEARCH BAR --}}
            <div class="ml-auto w-52 shrink-0">

                <form action="#" method="GET" class="relative">

                    <input
                        type="search"
                        name="search"
                        placeholder="Search recipes..."
                        aria-label="Search recipes"
                        class="block h-10 w-full rounded-full border border-orange-100 bg-orange-50/50 py-2 pl-10 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-300 focus:bg-white focus:ring-2 focus:ring-orange-100"
                    >

                    {{-- SEARCH ICON --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="16" height="16"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="pointer-events-none absolute left-3 top-1/2 !h-4 !w-4 -translate-y-1/2 text-orange-400"
                         aria-hidden="true">

                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>

                    </svg>

                </form>

            </div>

            @auth
            {{-- FAVORITE RECIPES --}}
            <a href="{{ route('favorites.index') }}"
               aria-label="Favorite Recipes"
               title="Favorite Recipes"
               class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-rose-100 bg-rose-50 text-rose-500 transition-colors hover:bg-rose-100 hover:text-rose-600 {{ request()->routeIs('favorites.index') ? 'ring-2 ring-rose-200' : '' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="20" height="20"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="h-5 w-5 shrink-0"
                     aria-hidden="true">

                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"></path>

                </svg>

            </a>

            {{-- NOTIFICATIONS --}}
            <button type="button"
                    aria-label="Notifications"
                    title="Notifications"
                    class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-orange-100 bg-orange-50 text-orange-500 transition-colors hover:bg-orange-100">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="20" height="20"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="h-5 w-5 shrink-0"
                     aria-hidden="true">

                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                    <path d="M10 21h4"></path>

                </svg>

            </button>

            {{-- USER PROFILE DROPDOWN --}}
            <details class="group relative shrink-0">

                <summary class="flex h-10 cursor-pointer list-none items-center gap-2 rounded-xl border border-orange-100 bg-white px-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-orange-50 [&::-webkit-details-marker]:hidden">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                        {{ Auth::user()->initials() }}
                    </span>

                    <span class="hidden max-w-28 truncate sm:block">
                        {{ Auth::user()->name }}
                    </span>

                    <span class="text-xs text-slate-400 transition-transform group-open:rotate-180" aria-hidden="true">
                        ⌄
                    </span>

                </summary>

                <div class="absolute right-0 top-full z-50 mt-2 w-56 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10">

                    <div class="border-b border-orange-50 px-3 py-2">

                        <p class="truncate text-sm font-bold text-slate-900">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="truncate text-xs text-slate-500">
                            {{ Auth::user()->email }}
                        </p>

                    </div>

                    <a href="{{ route('profile.show') }}"
                       class="mt-1 block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-orange-50 hover:text-orange-600">

                        My Profile

                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-orange-50 hover:text-orange-600">

                        Account Settings

                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50">

                            Log out

                        </button>
                    </form>

                </div>
            </details>
            @else
                <div class="ml-auto flex shrink-0 items-center gap-2">
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-bold text-slate-600 transition hover:bg-orange-50 hover:text-orange-600">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-orange-500 px-3 py-2 text-sm font-bold text-white transition hover:bg-orange-600">Sign up</a>
                </div>
            @endauth

        </div>
    </div>

</header>