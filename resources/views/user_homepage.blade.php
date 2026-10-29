@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen flex-col bg-[#FAF6F0] text-slate-800 antialiased dark:bg-slate-900 dark:text-slate-100">
        @include('partials.kitchhug_header')
        <main class="flex-1">
            <section data-dish-carousel class="relative isolate overflow-hidden bg-orange-50 flex items-center" style="min-height: calc(100vh - 64px);" role="region" aria-label="Dish inspiration" aria-roledescription="carousel">
                <div class="absolute inset-0" aria-hidden="true">
                    <article data-dish-slide role="group" aria-roledescription="slide" aria-label="1 of 4" class="absolute inset-0">
                        <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&amp;fit=crop&amp;w=1920&amp;q=85" alt="" class="h-full w-full object-cover" width="1920" height="1080" fetchpriority="high">
                    </article>
                    <article data-dish-slide role="group" aria-roledescription="slide" aria-label="2 of 4" class="absolute inset-0 hidden" aria-hidden="true">
                        <img src="https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&amp;fit=crop&amp;w=1920&amp;q=85" alt="" class="h-full w-full object-cover" width="1920" height="1080" loading="lazy">
                    </article>
                    <article data-dish-slide role="group" aria-roledescription="slide" aria-label="3 of 4" class="absolute inset-0 hidden" aria-hidden="true">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&amp;fit=crop&amp;w=1920&amp;q=85" alt="" class="h-full w-full object-cover" width="1920" height="1080" loading="lazy">
                    </article>
                    <article data-dish-slide role="group" aria-roledescription="slide" aria-label="4 of 4" class="absolute inset-0 hidden" aria-hidden="true">
                        <img src="https://images.unsplash.com/photo-1552332386-f8dd00dc2f85?auto=format&amp;fit=crop&amp;w=1920&amp;q=85" alt="" class="h-full w-full object-cover" width="1920" height="1080" loading="lazy">
                    </article>
                </div>
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-orange-50/95 via-orange-50/60 to-transparent"></div>
                <div class="pointer-events-none absolute inset-0 bg-white/10"></div>
                <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-orange-300/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 left-10 h-72 w-72 rounded-full bg-white/60 blur-3xl"></div>
                <div class="relative z-10 mx-auto max-w-7xl w-full px-4 py-16 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-xs font-bold text-orange-600 shadow-sm">Welcome, {{ Auth::user()->first_name ?? explode(' ', Auth::user()->name)[0] }}!</div>
                            <h1 class="mt-5 text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">What are we cooking, <span class="text-orange-500">{{ Auth::user()->name ?? 'Chef' }}</span>?</h1>
                            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">Your kitchen starts here. Save your favorite recipes, discover something delicious, and keep everything organized in one place.</p>
                            <div class="mt-7 flex flex-col gap-3 sm:flex-row"><a href="{{ route('recipes.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">＋ Add New Recipe</a><a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-orange-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-orange-50">My Profile <span>→</span></a></div>
                    </div>
                </div>
            </section>
            <div class="mx-auto max-w-7xl space-y-12 px-4 py-10 sm:px-6 lg:px-8">
                <section data-community-media-tabs>
                    <div class="border-b border-orange-100">
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">From the community</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Recipes shared by home cooks</h2>
                        <div class="mt-5 flex gap-8" role="tablist" aria-label="Community recipe media">
                            <button id="community-images-tab" type="button" role="tab" aria-controls="community-images-panel" aria-selected="true" tabindex="0" data-community-media-tab="images" class="border-b-2 border-orange-500 px-1 pb-3 text-sm font-semibold text-orange-700">Images</button>
                            <button id="community-videos-tab" type="button" role="tab" aria-controls="community-videos-panel" aria-selected="false" tabindex="-1" data-community-media-tab="videos" class="border-b-2 border-transparent px-1 pb-3 text-sm font-semibold text-slate-500 transition hover:text-slate-800">Videos</button>
                        </div>
                    </div>
                    <section id="community-images-panel" role="tabpanel" aria-labelledby="community-images-tab" data-community-media-panel="images" class="mt-6">
                        <div class="border-b border-orange-100 pb-4">
                            <h3 id="community-images-heading" class="text-xl font-black text-slate-900">Images</h3>
                            <p class="mt-1 text-sm text-slate-500">Recipe photos shared by home cooks.</p>
                        </div>
                        @if ($imageRecipes->isEmpty())
                            <div class="mt-6 rounded-3xl border border-orange-100 bg-white px-6 py-10 text-center shadow-sm">
                                <h3 class="text-lg font-bold text-slate-900">No community recipes yet</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Be the first home cook to share a recipe image.</p>
                                <a href="{{ route('recipes.create') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">Add a recipe</a>
                            </div>
                        @else
                            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach ($imageRecipes as $recipe)
                                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="group block">
                                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                                <span class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-800">{{ $recipe->category }}</span>
                                            </div>
                                            <div class="p-5">
                                                <h3 class="text-lg font-bold leading-snug text-slate-900 group-hover:text-orange-600">{{ $recipe->title }}</h3>
                                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                            </div>
                                        </a>
                                        <div class="px-5 pb-4">
                                            <div class="flex items-center justify-between gap-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                                                <a href="{{ route('users.show', $recipe->user) }}" class="inline-flex items-center gap-2 font-semibold text-slate-700 hover:text-orange-700">
                                                    <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-orange-100 text-xs font-bold text-orange-800">
                                                        @if ($recipe->user->profile_photo_path)
                                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->user->profile_photo_path) }}" alt="{{ $recipe->user->name }} profile photo" class="h-full w-full object-cover">
                                                        @else
                                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($recipe->user->name, 0, 1)) }}
                                                        @endif
                                                    </span>
                                                    {{ $recipe->user->name }}
                                                </a>
                                                <button type="button" data-share-recipe
                                                        data-share-url="{{ route('recipes.show', $recipe) }}"
                                                        data-share-title="{{ $recipe->title }}"
                                                        data-share-text="{{ $recipe->description }}"
                                                        aria-label="Share post"
                                                        title="Share post"
                                                        class="rounded-lg p-1.5 text-slate-500 transition hover:text-orange-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true"><polyline points="15 17 20 12 15 7"/><path d="M4 18v-2a4 4 0 0 1 4-4h12"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        @include('partials.recipe-post-actions', ['recipe' => $recipe])
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section id="community-videos-panel" role="tabpanel" aria-labelledby="community-videos-tab" data-community-media-panel="videos" class="mt-6" hidden>
                        <div class="border-b border-orange-100 pb-4">
                            <h3 id="community-videos-heading" class="text-xl font-black text-slate-900">Videos</h3>
                            <p class="mt-1 text-sm text-slate-500">Cooking videos shared by home cooks.</p>
                        </div>
                        @if ($videoRecipes->isEmpty())
                            <div class="mt-6 rounded-3xl border border-slate-200 bg-white px-6 py-10 text-center shadow-sm">
                                <h3 class="text-lg font-bold text-slate-900">No recipe videos yet</h3>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Videos shared with a recipe will appear here.</p>
                            </div>
                        @else
                            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach ($videoRecipes as $recipe)
                                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                        <div class="bg-slate-950">
                                            <video controls preload="metadata" data-recipe-video-player class="aspect-video w-full" aria-label="Recipe video for {{ $recipe->title }}">
                                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->video_path) }}">
                                                Your browser does not support video playback.
                                            </video>
                                        </div>
                                        <div class="p-5">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-orange-700">{{ $recipe->category }}</p>
                                            <h3 class="mt-2 text-lg font-bold leading-snug text-slate-900"><a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-600">{{ $recipe->title }}</a></h3>
                                            <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                            <div class="mt-4 flex items-center justify-between gap-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                                                <a href="{{ route('users.show', $recipe->user) }}" class="inline-flex items-center gap-2 font-semibold text-slate-700 hover:text-orange-700">
                                                    <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-orange-100 text-xs font-bold text-orange-800">
                                                        @if ($recipe->user->profile_photo_path)
                                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->user->profile_photo_path) }}" alt="{{ $recipe->user->name }} profile photo" class="h-full w-full object-cover">
                                                        @else
                                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($recipe->user->name, 0, 1)) }}
                                                        @endif
                                                    </span>
                                                    {{ $recipe->user->name }}
                                                </a>
                                                <button type="button" data-share-recipe
                                                        data-share-url="{{ route('recipes.show', $recipe) }}"
                                                        data-share-title="{{ $recipe->title }}"
                                                        data-share-text="{{ $recipe->description }}"
                                                        aria-label="Share post"
                                                        title="Share post"
                                                        class="rounded-lg p-1.5 text-slate-500 transition hover:text-orange-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true"><polyline points="15 17 20 12 15 7"/><path d="M4 18v-2a4 4 0 0 1 4-4h12"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        @include('partials.recipe-post-actions', ['recipe' => $recipe])
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <div class="mt-6 text-right">
                        <a href="{{ route('search.index') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700">Find more recipes and cooks →</a>
                    </div>
                </section>
                <section class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-lg"><div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div><div class="relative flex flex-col justify-between gap-6 px-6 py-8 sm:px-10 sm:py-10 md:flex-row md:items-center"><div class="max-w-2xl"><div class="text-xs font-extrabold uppercase tracking-widest text-orange-400">Kitchen Tip</div><h2 class="mt-3 text-2xl font-black sm:text-3xl">Keep your herbs fresh for longer.</h2><p class="mt-3 text-sm leading-7 text-slate-300 sm:text-base">Store fresh herbs in a glass of water and loosely cover them with a reusable bag before placing them in the refrigerator.</p></div><button type="button" id="open-hugsy-tips" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">More Tips →</button></div></section>
            </div>
        </main>
        @include('partials.kitchhug_footer')
    </div>
    <script>
        (() => {
            const carousel = document.querySelector('[data-dish-carousel]');

            if (!carousel) {
                return;
            }

            const slides = Array.from(carousel.querySelectorAll('[data-dish-slide]'));
            let currentSlide = 0;

            const showSlide = (index) => {
                currentSlide = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    const isActive = slideIndex === currentSlide;
                    slide.classList.toggle('hidden', !isActive);
                    slide.setAttribute('aria-hidden', String(!isActive));
                });
            };

            showSlide(currentSlide);

            window.setInterval(() => {
                if (!carousel.matches(':hover, :focus-within') && !document.hidden) {
                    showSlide(currentSlide + 1);
                }
            }, 5000);
        })();
    </script>
@endsection