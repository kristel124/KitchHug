@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <header class="mb-8">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">Discover the community</p>
                <h1 class="mt-1 text-3xl font-black text-slate-900">Search recipes and cooks</h1>
                @if ($search !== '')
                    <p class="mt-2 text-sm text-slate-500">Results for “{{ $search }}”</p>
                @else
                    <p class="mt-2 text-sm text-slate-500">Browse the latest recipes and find home cooks.</p>
                @endif
            </header>

            <section aria-labelledby="search-recipes-heading">
                <div class="mb-5 flex items-end justify-between border-b border-orange-100 pb-3">
                    <h2 id="search-recipes-heading" class="text-2xl font-black text-slate-900">Recipes</h2>
                    <span class="text-sm text-slate-500">{{ $recipes->total() }} {{ \Illuminate\Support\Str::plural('recipe', $recipes->total()) }}</span>
                </div>
                @if ($recipes->isEmpty())
                    <p class="rounded-2xl border border-orange-100 bg-white p-6 text-sm text-slate-500">No matching recipes found.</p>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($recipes as $recipe)
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                <a href="{{ route('recipes.show', $recipe) }}" class="group block">
                                    <div class="relative h-48 overflow-hidden bg-slate-100">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="h-full w-full object-cover" loading="lazy">
                                        <span class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-800">{{ $recipe->category }}</span>
                                        @if ($recipe->video_path)
                                            <span class="absolute right-3 top-3 rounded-md bg-slate-950/75 px-2.5 py-1 text-[11px] font-semibold text-white">Video</span>
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold leading-snug text-slate-900 group-hover:text-orange-600">{{ $recipe->title }}</h3>
                                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                        <p class="mt-4 text-sm font-semibold text-orange-700">View recipe <span aria-hidden="true">→</span></p>
                                    </div>
                                </a>
                                <div class="mx-5 flex items-center justify-between gap-2 border-t border-slate-100 py-4 text-xs text-slate-500">
                                    <span>By <a href="{{ route('users.show', $recipe->user) }}" class="font-semibold text-slate-700 hover:text-orange-700">{{ $recipe->user->name }}</a></span>
                                    <span>{{ $recipe->prep_time_minutes }} min</span>
                                </div>
                                @include('partials.recipe-post-actions', ['recipe' => $recipe])
                            </article>
                        @endforeach
                    </div>
                    <div class="mt-5">{{ $recipes->links() }}</div>
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
