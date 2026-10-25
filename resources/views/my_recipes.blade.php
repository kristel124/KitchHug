@extends('layouts.app')

@section('content')
    @php
        $savedRecipes = [
            ['name' => 'Warm Chicken Noodle', 'category' => 'Soup', 'time' => '25 mins', 'rating' => '4.9', 'emoji' => '🍲', 'color' => 'from-orange-100 to-amber-50', 'description' => 'A cozy homestyle classic with fresh vegetables and seasoned broth.'],
            ['name' => 'Fluffy Maple Pancakes', 'category' => 'Breakfast', 'time' => '15 mins', 'rating' => '4.8', 'emoji' => '🥞', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Golden buttermilk pancakes served with warm maple syrup and butter.'],
            ['name' => 'Garlic Cream Fettuccine', 'category' => 'Pasta', 'time' => '30 mins', 'rating' => '4.9', 'emoji' => '🍝', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Rich parmesan cream sauce tossed with roasted garlic and herbs.'],
            ['name' => 'Fresh Garden Salad', 'category' => 'Healthy', 'time' => '10 mins', 'rating' => '4.7', 'emoji' => '🥗', 'color' => 'from-emerald-100 to-lime-50', 'description' => 'Crisp seasonal vegetables with a light homemade dressing.'],
        ];
    @endphp

    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Your collection</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Favorite Recipes</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">Keep the recipes you love in one place and find your next meal quickly.</p>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Saved recipes</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ count($savedRecipes) }} recipes in your box</p>
                    </div>
                    <select class="rounded-lg border border-orange-100 bg-white px-3 py-2 text-sm text-slate-600 outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100" aria-label="Sort recipes">
                        <option>Recently added</option>
                        <option>Highest rated</option>
                        <option>Shortest time</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($savedRecipes as $recipe)
                        <article class="group overflow-hidden rounded-3xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            <div class="relative flex h-48 items-center justify-center bg-gradient-to-br {{ $recipe['color'] }} text-7xl">
                                {{ $recipe['emoji'] }}
                                <span class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm">⏱ {{ $recipe['time'] }}</span>
                                <button type="button" class="absolute left-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/95 text-rose-500 shadow-sm" aria-label="Remove from favorites">♥</button>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $recipe['category'] }}</span>
                                    <span class="rounded-md bg-slate-50 px-2 py-1 text-xs font-bold text-slate-600">⭐ {{ $recipe['rating'] }}</span>
                                </div>
                                <h3 class="mt-3 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $recipe['name'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $recipe['description'] }}</p>
                                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <span class="text-xs font-semibold text-slate-400">Saved recipe</span>
                                    <a href="#" class="text-sm font-bold text-orange-500 hover:text-orange-600">View Recipe →</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
