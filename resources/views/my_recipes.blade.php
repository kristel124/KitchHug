@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Your collection</p>
                    <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Favorite Recipes</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">Keep the dishes you love close at hand.</p>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('status'))
                    <p role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
                @endif

                @php($savedCount = $recipes->count() + $communityRecipes->count())
                <div class="mb-6 border-b border-orange-100 pb-4">
                    <h2 class="text-2xl font-black text-slate-900">Saved recipes</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $savedCount }} {{ \Illuminate\Support\Str::plural('recipe', $savedCount) }} in your box</p>
                </div>

                @if ($savedCount === 0)
                    <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-14 text-center">
                        <h3 class="mt-4 text-xl font-black text-slate-900">No favorites saved yet</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Browse recipes and save the dishes you’d like to make.</p>
                        <a href="{{ route('recipes.category', 'chicken') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">Explore Recipes</a>
                    </div>
                @else
                    @if ($communityRecipes->isNotEmpty())
                        <div class="mb-10">
                            <h3 class="mb-4 text-xl font-bold text-slate-900">Community recipes</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($communityRecipes as $recipe)
                                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="group block">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="h-48 w-full object-cover" loading="lazy">
                                            <div class="p-5">
                                                <p class="text-xs font-semibold uppercase tracking-wide text-orange-700">{{ $recipe->category }}</p>
                                                <h4 class="mt-2 text-lg font-bold text-slate-900 group-hover:text-orange-600">{{ $recipe->title }}</h4>
                                                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $recipe->description }}</p>
                                                @if ($recipe->video_path)
                                                    <span class="mt-3 inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">Video included</span>
                                                @endif
                                            </div>
                                        </a>
                                        <form method="POST" action="{{ route('favorites.toggle') }}" class="px-5 pb-5">
                                            @csrf
                                            <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50" aria-label="Remove {{ $recipe->title }} from favorites">Remove from Favorites</button>
                                        </form>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($recipes->isNotEmpty())
                        <div>
                            <h3 class="mb-4 text-xl font-bold text-slate-900">KitchHug recipes</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($recipes as $recipe)
                                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                        <a href="{{ route('recipes.curated.show', [$recipe['category_slug'], $recipe['recipe_slug']]) }}" class="block">
                                            <div class="relative h-48 overflow-hidden">
                                                <img src="{{ $recipe['image_url'] }}" alt="{{ $recipe['name'] }} recipe" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                                <span class="absolute right-4 top-4 rounded-md bg-white/95 px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm">{{ $recipe['time'] }}</span>
                                            </div>
                                            <div class="p-5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $recipe['category'] }}</span>
                                                    <span class="rounded-md bg-slate-50 px-2 py-1 text-xs font-semibold text-slate-600">Rating {{ $recipe['rating'] }}</span>
                                                </div>
                                                <h4 class="mt-3 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $recipe['name'] }}</h4>
                                                <p class="mt-1 text-xs font-semibold text-slate-400">Shared by KitchHug Kitchen</p>
                                                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $recipe['description'] }}</p>
                                                <span class="mt-5 inline-block border-t border-slate-100 pt-4 text-sm font-bold text-orange-500">View Recipe →</span>
                                            </div>
                                        </a>
                                        <form method="POST" action="{{ route('favorites.toggle') }}" class="px-5 pb-5">
                                            @csrf
                                            <input type="hidden" name="category" value="{{ $recipe['category_slug'] }}">
                                            <input type="hidden" name="recipe_slug" value="{{ $recipe['recipe_slug'] }}">
                                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50" aria-label="Remove {{ $recipe['name'] }} from favorites">Remove from Favorites</button>
                                        </form>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
