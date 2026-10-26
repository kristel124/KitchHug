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
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                            <div class="relative h-48 overflow-hidden bg-gradient-to-br {{ $recipe['color'] }}">
                                <img src="{{ $recipe['image_url'] }}" alt="{{ $recipe['name'] }} recipe" class="h-full w-full object-cover" loading="lazy">
                                <span class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-800">{{ $title }}</span>
                                <span class="absolute right-3 top-3 rounded-md bg-white/95 px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm">{{ $recipe['time'] }}</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-xs font-medium text-slate-500">KitchHug Kitchen</span>
                                    <span class="text-xs font-medium text-slate-500">Rating {{ $recipe['rating'] }}</span>
                                </div>
                                <h3 class="mt-2 text-lg font-bold leading-snug text-slate-900 group-hover:text-orange-600">{{ $recipe['name'] }}</h3>
                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $recipe['description'] }}</p>
                                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <a href="{{ route('recipes.curated.show', [$category_slug, $recipeSlug]) }}" class="text-sm font-semibold text-orange-700 transition hover:text-orange-800">View recipe <span aria-hidden="true">→</span></a>
                                    @auth
                                        <form method="POST" action="{{ route('favorites.toggle') }}">
                                            @csrf
                                            <input type="hidden" name="category" value="{{ $category_slug }}">
                                            <input type="hidden" name="recipe_slug" value="{{ $recipeSlug }}">
                                            <button type="submit" class="rounded-lg border px-3 py-1.5 text-sm font-semibold transition {{ $isFavorite ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-slate-200 bg-white text-slate-700 hover:border-orange-200 hover:bg-orange-50' }}" aria-label="{{ $isFavorite ? 'Remove '.$recipe['name'].' from favorites' : 'Add '.$recipe['name'].' to favorites' }}">
                                                {{ $isFavorite ? 'Saved' : 'Save recipe' }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:border-orange-200 hover:bg-orange-50" aria-label="Log in to save {{ $recipe['name'] }}">Save recipe</a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mb-6 mt-12 border-b border-orange-100 pb-4">
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">From the community</p>
                    <h2 class="mt-1 text-2xl font-black text-slate-900">Community {{ $title }}</h2>
                </div>
                @if ($communityRecipes->isEmpty())
                    <p class="rounded-2xl border border-orange-100 bg-white p-6 text-sm text-slate-500">No community recipes in this collection yet.</p>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($communityRecipes as $communityRecipe)
                            <article class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                <a href="{{ route('recipes.show', $communityRecipe) }}" class="group block">
                                    <div class="relative h-48 overflow-hidden bg-orange-50">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($communityRecipe->image_path) }}" alt="Recipe photo for {{ $communityRecipe->title }}" class="h-full w-full object-cover" loading="lazy">
                                        @if ($communityRecipe->video_path)
                                            <span class="absolute bottom-3 right-3 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-bold text-white">Video</span>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <div class="flex flex-wrap gap-2 text-[11px] font-extrabold uppercase tracking-widest text-orange-500">
                                            <span>{{ $communityRecipe->recipe_type }}</span>
                                            @if ($communityRecipe->method)
                                                <span>· {{ $communityRecipe->method }}</span>
                                            @endif
                                        </div>
                                        <h3 class="mt-2 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $communityRecipe->title }}</h3>
                                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500">{{ $communityRecipe->description }}</p>
                                        <p class="mt-4 text-sm font-bold text-orange-600">View full recipe →</p>
                                    </div>
                                </a>
                                <div class="mx-5 flex items-center justify-between gap-2 border-t border-slate-100 py-4 text-xs text-slate-500">
                                    <span>By <a href="{{ route('users.show', $communityRecipe->user) }}" class="font-semibold text-slate-700 hover:text-orange-700">{{ $communityRecipe->user->name }}</a></span>
                                    <span>{{ $communityRecipe->prep_time_minutes }} min</span>
                                </div>
                                @include('partials.recipe-post-actions', ['recipe' => $communityRecipe])
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection