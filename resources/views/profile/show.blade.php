@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/70 to-[#FAF6F0]">
                <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="rounded-3xl border border-orange-100 bg-white p-5 shadow-sm sm:p-8">
                        <div class="grid grid-cols-[96px_minmax(0,1fr)] items-center gap-5 sm:grid-cols-[160px_minmax(0,1fr)] sm:gap-10">
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-4 border-orange-100 bg-slate-900 text-2xl font-black text-white shadow-sm sm:h-36 sm:w-36">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}" alt="{{ $user->name }} profile photo" class="h-full w-full object-cover">
                                    @else
                                        {{ $user->initials() }}
                                    @endif
                                </div>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">My profile</p>
                                <h1 class="mt-1 break-words text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $user->name }}</h1>
                                <p class="mt-1 truncate text-xs text-slate-500 sm:text-sm">{{ $user->email }}</p>
                                <p class="mt-1 text-[11px] text-slate-400 sm:text-xs">Member since {{ $user->created_at->format('F Y') }}</p>

                                <dl class="mt-5 flex flex-wrap gap-x-10 gap-y-3 sm:mt-7">
                                    <div class="min-w-[4rem]">
                                        <dd class="text-lg font-black text-slate-900 sm:text-xl">{{ $totalRecipeCount }}</dd>
                                        <dt class="text-[10px] font-bold text-slate-500 sm:text-xs">Recipes</dt>
                                    </div>
                                    <button type="button" onclick="openFollowModal({{ $user->id }}, 'followers')" class="group min-w-[4rem] text-left">
                                        <dd class="text-lg font-black text-slate-900 transition group-hover:text-orange-600 sm:text-xl">{{ $followersCount }}</dd>
                                        <dt class="text-[10px] font-bold text-slate-500 transition group-hover:text-orange-500 sm:text-xs">Followers</dt>
                                    </button>
                                    <button type="button" onclick="openFollowModal({{ $user->id }}, 'following')" class="group min-w-[4rem] text-left">
                                        <dd class="text-lg font-black text-slate-900 transition group-hover:text-orange-600 sm:text-xl">{{ $followingCount }}</dd>
                                        <dt class="text-[10px] font-bold text-slate-500 transition group-hover:text-orange-500 sm:text-xs">Following</dt>
                                    </button>
                                    <div class="min-w-[4rem]">
                                        <dd class="text-lg font-black text-slate-900 sm:text-xl">{{ $totalLikesCount }}</dd>
                                        <dt class="text-[10px] font-bold text-slate-500 sm:text-xs">Likes</dt>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-3 border-t border-orange-100 pt-5 sm:grid-cols-2">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-orange-100">Edit profile</a>
                            <a href="{{ route('recipes.create') }}" class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-600">Share a recipe</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif

                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">From your kitchen</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Your recipes</h2>
                    </div>
                    <span class="rounded-full border border-orange-100 bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm">{{ $recipes->count() }} of {{ $totalRecipeCount }} {{ \Illuminate\Support\Str::plural('recipe', $totalRecipeCount) }}</span>
                </div>

                @if ($totalRecipeCount === 0)
                    <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-14 text-center">
                        <h3 class="mt-4 text-xl font-black text-slate-900">Your recipe collection starts here</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Share a favorite dish with the KitchHug community. Add the ingredients and steps so other home cooks can make it too.</p>
                        <a href="{{ route('recipes.create') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">＋ Post your first recipe</a>
                    </div>
                @else
                    <form method="GET" action="{{ route('profile.show') }}" class="mb-6 grid gap-3 rounded-2xl border border-orange-100 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
                        <label class="sm:col-span-2 lg:col-span-1">
                            <span class="mb-1 block text-xs font-bold text-slate-600">Find a recipe</span>
                            <input type="search" name="search" value="{{ $search }}" placeholder="Search by title..." class="w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-3 py-2.5 text-sm outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </label>
                        <label>
                            <span class="mb-1 block text-xs font-bold text-slate-600">Course</span>
                            <select name="category" class="w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-3 py-2.5 text-sm outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="">All courses</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span class="mb-1 block text-xs font-bold text-slate-600">Method</span>
                            <select name="method" class="w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-3 py-2.5 text-sm outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="">All methods</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method }}" @selected(($filters['method'] ?? '') === $method)>{{ $method }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span class="mb-1 block text-xs font-bold text-slate-600">Recipe type</span>
                            <select name="recipe_type" class="w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-3 py-2.5 text-sm outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="">All recipe types</option>
                                @foreach ($recipeTypes as $recipeType)
                                    <option value="{{ $recipeType }}" @selected(($filters['recipe_type'] ?? '') === $recipeType)>{{ $recipeType }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span class="mb-1 block text-xs font-bold text-slate-600">Sort by</span>
                            <select name="sort" data-profile-sort-select class="w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-3 py-2.5 text-sm outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest first</option>
                                <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest first</option>
                                <option value="title" @selected(($filters['sort'] ?? '') === 'title')>Title A–Z</option>
                            </select>
                        </label>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600">Apply</button>
                            <a href="{{ route('profile.show') }}" class="rounded-xl border border-orange-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-orange-50">Clear</a>
                        </div>
                    </form>

                    @if ($recipes->isEmpty())
                        <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-12 text-center">
                            <h3 class="text-xl font-black text-slate-900">No recipes match these filters</h3>
                            <p class="mt-2 text-sm text-slate-500">Try another search or clear the filters to see all your recipes.</p>
                            <a href="{{ route('profile.show') }}" class="mt-5 inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">Clear filters</a>
                        </div>
                    @else
                    <div class="mb-4 flex justify-end gap-2" role="group" aria-label="Recipe display options">
                        <button type="button" data-profile-recipe-view="grid" aria-pressed="true" class="rounded-lg border border-orange-200 bg-orange-50 px-3 py-2 text-sm font-bold text-orange-700">Grid</button>
                        <button type="button" data-profile-recipe-view="list" aria-pressed="false" class="rounded-lg border border-orange-100 bg-white px-3 py-2 text-sm font-bold text-slate-600 hover:bg-orange-50">List</button>
                    </div>
                    <div data-profile-recipes-grid class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($recipes as $recipe)
                            <article data-profile-recipe-card class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                <div data-profile-recipe-image class="relative h-52 overflow-hidden bg-slate-100">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/70 to-transparent px-4 pb-4 pt-12">
                                        <span class="inline-flex rounded-md bg-white/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-800">{{ $recipe->category }}</span>
                                    </div>
                                    @if ($recipe->video_path)
                                        <span class="absolute right-3 top-3 rounded-md bg-slate-950/75 px-2.5 py-1 text-[11px] font-semibold text-white">Video</span>
                                    @endif
                                </div>
                                <div data-profile-recipe-content class="p-5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="text-lg font-bold leading-snug text-slate-900"><a href="{{ route('recipes.show', $recipe) }}" class="transition hover:text-orange-600">{{ $recipe->title }}</a></h3>
                                            <p class="mt-1.5 text-xs font-medium text-slate-500">Published {{ $recipe->created_at->format('M j, Y') }}</p>
                                        </div>
                                        <span class="shrink-0 rounded-md bg-orange-50 px-2.5 py-1.5 text-xs font-semibold text-orange-800">{{ $recipe->prep_time_minutes }} min</span>
                                    </div>
                                    <p class="mt-3 line-clamp-3 min-h-[4.5rem] text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @if ($recipe->recipe_type)
                                            <span class="rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600">{{ $recipe->recipe_type }}</span>
                                        @endif
                                        @if ($recipe->method)
                                            <span class="rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600">{{ $recipe->method }}</span>
                                        @endif
                                    </div>


                                </div>
                                @include('partials.recipe-post-actions', ['recipe' => $recipe])
                            </article>
                        @endforeach
                    </div>
                    @endif
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>

    @include('profile.partials.follow-modal')
@endsection
