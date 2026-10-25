@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @if (auth()->check())
            @include('partials.kitchhug_header')
        @else
            @include('partials.public_header')
        @endif

        <main>
            <section class="relative isolate overflow-hidden border-b border-orange-100 bg-gradient-to-br from-orange-100 via-[#FFF9F1] to-[#FAF6F0]">
                <div class="pointer-events-none absolute -right-20 -top-28 h-80 w-80 rounded-full bg-orange-300/25 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-36 left-1/3 h-72 w-72 rounded-full bg-amber-200/30 blur-3xl"></div>
                <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
                    <div class="grid items-center gap-10 md:grid-cols-[minmax(0,1fr)_260px] lg:gap-16">
                        <div class="max-w-3xl">
                            <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}" class="inline-flex items-center gap-2 rounded-full border border-orange-200/80 bg-white/80 px-4 py-2 text-sm font-bold text-orange-700 shadow-sm backdrop-blur transition hover:border-orange-300 hover:bg-white">
                                <span aria-hidden="true">←</span> Home
                            </a>
                            <p class="mt-7 text-xs font-extrabold uppercase tracking-[0.22em] text-orange-600">Made for your table</p>
                            <h1 class="mt-2 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">{{ $title }}</h1>
                            <p class="mt-4 max-w-2xl text-base leading-7 text-slate-700 sm:text-lg">{{ $description }}</p>
                            <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-slate-600">
                                <span class="h-2 w-2 rounded-full bg-orange-500" aria-hidden="true"></span>
                                <span>Simple ideas, delicious moments</span>
                            </div>
                        </div>
                        <div class="relative mx-auto flex h-52 w-52 items-center justify-center sm:h-60 sm:w-60">
                            <div class="absolute inset-0 rotate-6 rounded-[2.5rem] bg-orange-200/60"></div>
                            <div class="absolute inset-2 -rotate-3 overflow-hidden rounded-[2.2rem] border-4 border-white shadow-xl">
                                <img src="{{ $recipes[0]['image_url'] }}" alt="{{ $recipes[0]['name'] }} dish" class="h-full w-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('status'))
                    <p role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
                @endif
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recipes as $recipe)
                        @php($recipeSlug = \Illuminate\Support\Str::slug($recipe['name']))
                        @php($isFavorite = in_array($recipeSlug, $favorite_slugs, true))
                        <article class="group overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br {{ $recipe['color'] }}">
                                <img src="{{ $recipe['image_url'] }}" alt="{{ $recipe['name'] }} recipe" class="h-full w-full object-cover" loading="lazy">
                                <span class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm">⏱ {{ $recipe['time'] }}</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $title }}</span>
                                    <span class="rounded-md bg-slate-50 px-2 py-1 text-xs font-bold text-slate-600">⭐ {{ $recipe['rating'] }}</span>
                                </div>
                                <h3 class="mt-3 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $recipe['name'] }}</h3>
                                <p class="mt-1 text-xs font-semibold text-slate-400">Shared by {{ $recipe['author'] ?? 'KitchHug Kitchen' }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $recipe['description'] }}</p>
                                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <a href="{{ route('recipes.curated.show', [$category_slug, $recipeSlug]) }}" class="text-sm font-bold text-orange-500 transition hover:text-orange-600">View Recipe →</a>
                                    @auth
                                        <form method="POST" action="{{ route('favorites.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="category" value="{{ $category_slug }}">
                                            <input type="hidden" name="recipe_slug" value="{{ $recipeSlug }}">
                                            <button type="submit" class="rounded-full px-3 py-1.5 text-sm font-bold transition {{ $isFavorite ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-slate-50 text-slate-500 hover:bg-rose-50 hover:text-rose-600' }}" aria-label="{{ $isFavorite ? 'Remove '.$recipe['name'].' from favorites' : 'Add '.$recipe['name'].' to favorites' }}">
                                                {{ $isFavorite ? '♥ Saved' : '♡ Save' }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="rounded-full bg-slate-50 px-3 py-1.5 text-sm font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" aria-label="Log in to save {{ $recipe['name'] }}">♡ Save</a>
                                    @endauth
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