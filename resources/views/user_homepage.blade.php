@extends('layouts.app')

@section('content')
    @php
        $recipes = [
            ['name' => 'Crispy Chicken', 'time' => '30 mins', 'category' => 'Main Course', 'rating' => '4.9', 'emoji' => '🍗', 'color' => 'from-orange-100 to-amber-50'],
            ['name' => 'Fluffy Pancakes', 'time' => '15 mins', 'category' => 'Breakfast', 'rating' => '4.8', 'emoji' => '🥞', 'color' => 'from-amber-100 to-orange-50'],
            ['name' => 'Garlic Cream Pasta', 'time' => '25 mins', 'category' => 'Pasta', 'rating' => '4.9', 'emoji' => '🍝', 'color' => 'from-rose-100 to-amber-50'],
            ['name' => 'Fresh Garden Salad', 'time' => '10 mins', 'category' => 'Healthy', 'rating' => '4.7', 'emoji' => '🥗', 'color' => 'from-emerald-100 to-lime-50'],
        ];
    @endphp

    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')
        <main class="flex-1">
            <section class="relative overflow-hidden bg-gradient-to-b from-orange-100/80 via-orange-50/60 to-[#FAF6F0]">
                <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-orange-300/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 left-10 h-72 w-72 rounded-full bg-white/60 blur-3xl"></div>
                <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                    <div class="flex flex-col justify-between gap-10 lg:flex-row lg:items-center">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-xs font-bold text-orange-600 shadow-sm">👋 Welcome back, Chef!</div>
                            <h1 class="mt-5 text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">What are we cooking, <span class="text-orange-500">{{ Auth::user()->name ?? 'Chef' }}</span>?</h1>
                            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">Your kitchen starts here. Save your favorite recipes, discover something delicious, and keep everything organized in one place.</p>
                            <div class="mt-7 flex flex-col gap-3 sm:flex-row"><a href="{{ route('recipes.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">＋ Add New Recipe</a><a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-orange-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-orange-50">My Profile <span>→</span></a></div>
                        </div>
                        <div class="hidden h-52 w-64 shrink-0 rotate-2 items-center justify-center rounded-3xl border border-white bg-white/80 shadow-lg transition-transform duration-300 hover:rotate-0 lg:flex"><div class="text-center"><div class="text-7xl">🥘</div><p class="mt-2 text-sm font-bold text-slate-800">Cook something good</p><p class="mt-1 text-[10px] font-semibold uppercase tracking-widest text-slate-400">One recipe at a time</p></div></div>
                    </div>
                </div>
            </section>
            <div class="mx-auto max-w-7xl space-y-12 px-4 py-10 sm:px-6 lg:px-8">
                <section>
                    <div class="flex items-end justify-between border-b border-orange-100 pb-4"><div><p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">Trending now</p><h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Top Recipes</h2></div><a href="#" class="hidden text-sm font-bold text-orange-500 hover:text-orange-600 sm:flex">View all →</a></div>
                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($recipes as $recipe)
                            <a href="{{ route('recipes.featured', \Illuminate\Support\Str::slug($recipe['name'])) }}" class="group overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"><div class="relative flex h-40 items-center justify-center bg-gradient-to-br {{ $recipe['color'] }} text-6xl">{{ $recipe['emoji'] }}<span class="absolute right-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold text-slate-700 shadow-sm">⭐ {{ $recipe['rating'] }}</span></div><div class="p-4"><p class="text-[10px] font-extrabold uppercase tracking-widest text-orange-500">{{ $recipe['category'] }}</p><h3 class="mt-1.5 font-extrabold text-slate-900 transition-colors group-hover:text-orange-500">{{ $recipe['name'] }}</h3><p class="mt-1 text-xs text-slate-400">⏱ {{ $recipe['time'] }}</p></div></a>
                        @endforeach
                    </div>
                </section>
                <section>
                    <div class="flex items-end justify-between border-b border-orange-100 pb-4"><div><p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">Your collection</p><h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Your Recipe Box</h2></div><a href="#" class="text-sm font-bold text-orange-500 hover:text-orange-600">View all →</a></div>
                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach (array_slice($recipes, 0, 3) as $recipe)
                            <article class="group relative overflow-hidden rounded-3xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"><a href="{{ route('recipes.featured', \Illuminate\Support\Str::slug($recipe['name'])) }}" class="block"><div class="relative flex h-52 items-center justify-center bg-gradient-to-br {{ $recipe['color'] }} text-7xl">{{ $recipe['emoji'] }}<span class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm">⏱ {{ $recipe['time'] }}</span></div><div class="p-6"><div class="flex items-center justify-between"><span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $recipe['category'] }}</span><span class="rounded-md bg-slate-50 px-2 py-1 text-xs font-bold text-slate-600">⭐ {{ $recipe['rating'] }}</span></div><h3 class="mt-3 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $recipe['name'] }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">A cozy homestyle favorite prepared with fresh ingredients and rich, comforting flavor.</p><div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-xs font-semibold text-slate-400">Easy to make</span><span class="text-sm font-bold text-orange-500">View Recipe →</span></div></div></a><button type="button" class="absolute left-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/95 text-rose-500 shadow-sm" aria-label="Favorite recipe">♥</button></article>
                        @endforeach
                    </div>
                </section>
                <section class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-lg"><div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div><div class="relative flex flex-col justify-between gap-6 px-6 py-8 sm:px-10 sm:py-10 md:flex-row md:items-center"><div class="max-w-2xl"><div class="text-xs font-extrabold uppercase tracking-widest text-orange-400">💡 Kitchen Tip</div><h2 class="mt-3 text-2xl font-black sm:text-3xl">Keep your herbs fresh for longer.</h2><p class="mt-3 text-sm leading-7 text-slate-300 sm:text-base">Store fresh herbs in a glass of water and loosely cover them with a reusable bag before placing them in the refrigerator.</p></div><a href="#" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">More Tips →</a></div></section>
            </div>
        </main>
        @include('partials.kitchhug_footer')
    </div>
@endsection