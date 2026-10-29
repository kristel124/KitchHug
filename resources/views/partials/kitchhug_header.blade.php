<header class="sticky top-0 z-50 border-b border-orange-100 bg-white/95 shadow-sm backdrop-blur-md dark:border-slate-700 dark:bg-slate-900/95">

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
            'Main Course',
            'Side Dish',
            'Snacks',
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
               class="group flex shrink-0 items-center transition-transform group-hover:scale-105">

                <img src="{{ asset('KitchHug_Logo.png') }}" alt="KitchHug — Your Recipe Box" class="h-14 w-auto object-contain">
            </a>

            {{-- DESKTOP NAVIGATION --}}
            <nav class="flex shrink-0 items-center gap-5 text-sm font-semibold">

                {{-- HOME --}}
                <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}"
                   class="relative py-2 transition-colors hover:text-orange-500
                   {{ request()->routeIs('user_homepage') ? 'text-orange-500' : 'text-slate-600 dark:text-slate-300' }}">

                    Home

                    @if(request()->routeIs('user_homepage'))
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full bg-orange-500"></span>
                    @endif
                </a>

                {{-- RECIPES DROPDOWN --}}
                <details data-navigation-dropdown class="group relative">

                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-slate-600 transition-colors hover:text-orange-500 dark:text-slate-300 [&::-webkit-details-marker]:hidden">
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

                    <div class="absolute left-0 top-full z-50 mt-2 max-h-80 w-56 overflow-y-auto rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10 dark:border-slate-700 dark:bg-slate-800">

                        @foreach($recipeCategories as $slug => $label)
                            <a href="{{ auth()->check() ? route('recipes.category', $slug) : route('recipes.public-category', $slug) }}"
                               class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 dark:text-slate-300 dark:hover:bg-slate-700">

                                {{ $label }}

                            </a>
                        @endforeach

                    </div>
                </details>

                {{-- MORE DROPDOWN --}}
                <details data-navigation-dropdown class="group relative">

                    <summary class="flex cursor-pointer list-none items-center gap-1 py-2 text-slate-600 transition-colors hover:text-orange-500 dark:text-slate-300 [&::-webkit-details-marker]:hidden">

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

                    <div class="absolute left-0 top-full z-50 mt-2 w-52 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10 dark:border-slate-700 dark:bg-slate-800">

                        {{-- BY METHODS --}}
                        <details data-navigation-dropdown class="group/method relative">

                            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 dark:text-slate-300 dark:hover:bg-slate-700 [&::-webkit-details-marker]:hidden">

                                By Methods

                                <span class="text-lg leading-none" aria-hidden="true">›</span>

                            </summary>

                            <div class="absolute left-full top-0 z-50 ml-1 w-48 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10 dark:border-slate-700 dark:bg-slate-800">

                                @foreach($methodCategories as $slug => $label)
                                    <a href="{{ route('recipes.by-method', $slug) }}"
                                       class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 dark:text-slate-300 dark:hover:bg-slate-700">

                                        {{ $label }}

                                    </a>
                                @endforeach

                            </div>
                        </details>

                        {{-- COURSE SUBMENU --}}
                        <details data-navigation-dropdown class="group/course relative">

                            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 dark:text-slate-300 dark:hover:bg-slate-700 [&::-webkit-details-marker]:hidden">

                                Course

                                <span class="text-lg leading-none" aria-hidden="true">›</span>

                            </summary>

                            <div class="absolute left-full top-0 z-50 ml-1 w-44 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10 dark:border-slate-700 dark:bg-slate-800">

                                @foreach($courseCategories as $category)
                                    <a href="{{ route('recipes.by-course', \Illuminate\Support\Str::slug($category)) }}"
                                       class="block rounded-lg px-3 py-2 text-sm text-slate-600 transition-colors hover:bg-orange-50 hover:text-orange-500 dark:text-slate-300 dark:hover:bg-slate-700">

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

                <form action="{{ route('search.index') }}" method="GET" class="relative">

                    <input
                        type="search"
                        name="search"
                        placeholder="Search recipes or cooks..."
                        aria-label="Search recipes or cooks"
                        value="{{ request('search') }}"
                        class="block h-10 w-full rounded-full border border-orange-100 bg-orange-50/50 py-2 pl-10 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-orange-300 focus:bg-white focus:ring-2 focus:ring-orange-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:bg-slate-700"
                    >

                    <button type="submit" aria-label="Search" class="absolute left-2 top-1/2 flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full text-orange-500 transition hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="h-4 w-4"
                             aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m16 16 4 4"></path>
                        </svg>
                    </button>

                </form>

            </div>

            @auth
            {{-- FAVORITE RECIPES --}}
            @php
                $savedRecipeCount = Auth::user()->recipeFavorites()->count();
            @endphp
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

                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>

                </svg>

                @if ($savedRecipeCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold leading-none text-white">{{ $savedRecipeCount > 99 ? '99+' : $savedRecipeCount }}</span>
                @endif

            </a>

            {{-- NOTIFICATIONS --}}
            @php
                $headerNotifications = Auth::user()->notifications()->latest()->take(5)->get();
                $unreadNotificationCount = Auth::user()->unreadNotifications()->count();
            @endphp
            <details data-navigation-dropdown class="group relative shrink-0">
                <summary aria-label="Notifications{{ $unreadNotificationCount > 0 ? ', '.$unreadNotificationCount.' unread' : '' }}"
                         title="Notifications"
                         class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-orange-100 bg-orange-50 text-orange-500 transition-colors hover:bg-orange-100 [&::-webkit-details-marker]:hidden">

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

                    @if ($unreadNotificationCount > 0)
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold leading-none text-white">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
                    @endif
                </summary>

                <div class="absolute right-0 z-50 mt-3 w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-700">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Notifications</h2>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $unreadNotificationCount }} unread</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-orange-700 hover:text-orange-800">View all</a>
                    </div>
                    @forelse ($headerNotifications as $notification)
                        <form method="POST" action="{{ route('notifications.open', $notification->id) }}">
                            @csrf
                            <button type="submit" class="flex w-full items-start gap-3 border-b border-slate-100 px-4 py-3 text-left transition hover:bg-orange-50 dark:border-slate-700 dark:hover:bg-slate-700 {{ $notification->read_at ? '' : 'bg-orange-50/60 dark:bg-slate-700/40' }}">
                                <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-transparent' : 'bg-orange-500' }}" aria-hidden="true"></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm leading-5 text-slate-700 dark:text-slate-200">{{ $notification->data['message'] ?? 'You have a new notification.' }}</span>
                                    <time class="mt-1 block text-xs text-slate-400" datetime="{{ $notification->created_at->toISOString() }}">{{ $notification->created_at->diffForHumans() }}</time>
                                </span>
                            </button>
                        </form>
                    @empty
                        <p class="px-4 py-6 text-center text-sm text-slate-500">No notifications yet.</p>
                    @endforelse
                    @if ($unreadNotificationCount > 0)
                        <form method="POST" action="{{ route('notifications.read-all') }}" class="border-t border-slate-100 p-3">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-sm font-semibold text-orange-700 hover:bg-orange-50">Mark all as read</button>
                        </form>
                    @endif
                </div>
            </details>

            {{-- USER PROFILE DROPDOWN --}}
            <details data-navigation-dropdown class="group relative shrink-0">

                <summary class="flex h-10 cursor-pointer list-none items-center gap-1.5 rounded-xl px-1 text-sm font-bold text-slate-700 transition hover:opacity-80 [&::-webkit-details-marker]:hidden">

                    <span class="relative flex shrink-0">
                        <span class="flex size-9 items-center justify-center overflow-hidden rounded-full bg-slate-700 text-sm font-black text-white ring-2 ring-white shadow">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                     alt="{{ Auth::user()->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                {{ Auth::user()->initials() }}
                            @endif
                        </span>
                        <span class="absolute -bottom-0.5 -right-0.5 size-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                    </span>

                    <span class="text-xs text-slate-400 transition-transform group-open:rotate-180" aria-hidden="true">
                        ⌄
                    </span>

                </summary></summary>

                <div class="absolute right-0 top-full z-50 mt-2 w-56 rounded-xl border border-orange-100 bg-white p-2 shadow-xl shadow-orange-900/10 dark:border-slate-700 dark:bg-slate-800">

                    <div class="border-b border-orange-50 px-3 py-2 dark:border-slate-700">

                        <p class="truncate text-sm font-bold text-slate-900 dark:text-white">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ Auth::user()->email }}
                        </p>

                    </div>

                    <a href="{{ route('profile.show') }}"
                       class="mt-1 block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-orange-50 hover:text-orange-600 dark:text-slate-200 dark:hover:bg-slate-700">

                        My Profile

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