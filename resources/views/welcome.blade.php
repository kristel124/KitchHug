<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Hug | Your Digital Recipe Box</title>
    
    <!-- Tailwind CSS Script -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-full bg-orange-50/30 text-slate-800 flex flex-col selection:bg-orange-500 selection:text-white antialiased font-sans">

    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-orange-500 focus:text-white">
        Skip to main content
    </a>

    @include('partials.public_header')

    <!-- ================= HERO SECTION ================= -->
    <main id="main-content" class="grow">

        <section data-carousel class="relative isolate overflow-hidden bg-orange-50 flex items-center" style="min-height: calc(100vh - 64px);">
            <div role="region" aria-roledescription="carousel" aria-label="Featured recipe backgrounds"
                 class="absolute inset-0">
                <article data-carousel-slide role="group" aria-roledescription="slide" aria-label="1 of 3" class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&amp;fit=crop&amp;w=1920&amp;q=85"
                         alt="Colorful fresh salad with vegetables and greens" class="h-full w-full object-cover" loading="eager" width="1920" height="1080">
                </article>
                <article data-carousel-slide role="group" aria-roledescription="slide" aria-label="2 of 3" class="absolute inset-0 hidden">
                    <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&amp;fit=crop&amp;w=1920&amp;q=85"
                         alt="A cozy homemade meal served on a rustic table" class="h-full w-full object-cover" loading="lazy" width="1920" height="1080">
                </article>
                <article data-carousel-slide role="group" aria-roledescription="slide" aria-label="3 of 3" class="absolute inset-0 hidden">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&amp;fit=crop&amp;w=1920&amp;q=85"
                         alt="Freshly baked pizza topped with colorful ingredients" class="h-full w-full object-cover" loading="lazy" width="1920" height="1080">
                </article>
            </div>
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-orange-50/95 via-orange-50/80 to-orange-50/40"></div>
            <div class="pointer-events-none absolute inset-0 bg-white/10"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                    <!-- Hero Content -->
                    <div class="lg:col-span-8 max-w-3xl">

                        <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-white/80 border border-orange-200/60 rounded-full text-xs sm:text-sm font-bold text-orange-700 shadow-sm backdrop-blur-sm">
                            <span role="img" aria-label="Fork and knife">🍴</span>
                            <span>Your recipes, all in one place</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-slate-900">
                            A little
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">hug</span>
                            for every recipe.
                        </h1>

                        <p class="mt-6 text-lg lg:text-xl text-slate-600 leading-relaxed max-w-xl">
                            Welcome to <strong>Kitchen Hug</strong> — your personal digital recipe box
                            for saving, organizing, and discovering delicious homemade meals.
                        </p>

                        <p class="mt-3 text-base text-slate-500 leading-relaxed max-w-xl">
                            Keep your favorite family recipes safe, discover new dishes,
                            and turn your kitchen ideas into beautiful, easy-to-follow cards.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                            <a href="#explore"
                               class="inline-flex items-center justify-center gap-2 px-7 py-4 bg-orange-500 text-white font-bold text-base rounded-xl shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-orange-500/35 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                Explore Recipes
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            <a href="/register"
                               class="inline-flex items-center justify-center px-7 py-4 bg-white text-slate-800 font-bold text-base rounded-xl border border-orange-200/80 shadow-sm hover:bg-orange-50/50 hover:border-orange-300 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500">
                                Start Cooking
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <div class="absolute bottom-5 right-5 z-20 flex items-center gap-3 sm:bottom-7 sm:right-8">
            </div>
        </section>

        <!-- ================= FEATURED RECIPES ================= -->
        <section id="explore" class="bg-white py-16 lg:py-20 border-y border-orange-100/50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-3 text-center sm:flex-row sm:items-end sm:justify-between sm:text-left">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-orange-500">From the community</span>
                        <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Recipes shared by home cooks</h2>
                        <p class="mt-2 text-slate-500">Find a new favorite to make and share.</p>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 text-sm font-bold text-orange-600 transition hover:text-orange-700">
                        Create your recipe box <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div data-community-media-tabs class="mt-8">
                    <div class="border-b border-slate-200">
                        <div class="flex gap-1" role="tablist" aria-label="Home recipe media">
                            <button id="home-images-tab" type="button" role="tab" aria-controls="home-images-panel" aria-selected="true" tabindex="0" data-community-media-tab="images"
                                class="relative px-4 pb-3 pt-1 text-sm font-semibold text-orange-600 transition after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:rounded-full after:bg-orange-500">
                                Images
                            </button>
                            <button id="home-videos-tab" type="button" role="tab" aria-controls="home-videos-panel" aria-selected="false" tabindex="-1" data-community-media-tab="videos"
                                class="relative px-4 pb-3 pt-1 text-sm font-semibold text-slate-500 transition hover:text-slate-800 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:rounded-full after:bg-transparent">
                                Videos
                            </button>
                        </div>
                    </div>

                    <div id="home-images-panel" role="tabpanel" aria-labelledby="home-images-tab" data-community-media-panel="images">
                        @if ($homeImageRecipes->isEmpty())
                            <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 px-6 py-10 text-center">
                                <h3 class="text-lg font-bold text-slate-900">No recipes yet</h3>
                                <p class="mt-2 text-sm text-slate-500">Be the first to share a recipe!</p>
                                <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-orange-600">Get started</a>
                            </div>
                        @else
                            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach ($homeImageRecipes as $recipe)
                                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="group block flex-1">
                                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}"
                                                     alt="Recipe photo for {{ $recipe->title }}"
                                                     class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                     loading="lazy">
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
                                                        @guest data-login-url="{{ route('login') }}" @endguest
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
                    </div>

                    <div id="home-videos-panel" role="tabpanel" aria-labelledby="home-videos-tab" data-community-media-panel="videos" class="pt-8" hidden>
                        @if ($homeVideoRecipes->isEmpty())
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-6 py-10 text-center">
                                <h3 class="text-lg font-bold text-slate-900">No recipe videos yet</h3>
                                <p class="mt-2 text-sm text-slate-500">Videos shared with recipes will appear here.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($homeVideoRecipes as $communityRecipe)
                                    <article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                        <div class="bg-slate-950">
                                            <video controls preload="metadata" data-recipe-video-player class="aspect-video w-full" aria-label="Recipe video for {{ $communityRecipe->title }}">
                                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($communityRecipe->video_path) }}">
                                                Your browser does not support video playback.
                                            </video>
                                        </div>
                                        <div class="flex-1 p-5">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-orange-700">{{ $communityRecipe->category }}</p>
                                            <h3 class="mt-2 text-lg font-bold text-slate-900"><a href="{{ route('recipes.show', $communityRecipe) }}" class="hover:text-orange-600">{{ $communityRecipe->title }}</a></h3>
                                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $communityRecipe->description }}</p>
                                            <a href="{{ route('users.show', $communityRecipe->user) }}" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-slate-700 hover:text-orange-700">
                                                <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-orange-100 text-xs font-bold text-orange-800">
                                                    @if ($communityRecipe->user->profile_photo_path)
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($communityRecipe->user->profile_photo_path) }}" alt="{{ $communityRecipe->user->name }} profile photo" class="h-full w-full object-cover">
                                                    @else
                                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($communityRecipe->user->name, 0, 1)) }}
                                                    @endif
                                                </span>
                                                {{ $communityRecipe->user->name }}
                                            </a>
                                        </div>
                                        @include('partials.recipe-post-actions', ['recipe' => $communityRecipe])
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= FEATURES ================= -->
        <section id="why-kitchen-hug" class="bg-white py-20 lg:py-28 border-b border-orange-100/50">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Section Heading -->
                <div class="text-center max-w-2xl mx-auto">

                    <span class="text-xs font-extrabold uppercase tracking-widest text-orange-500 bg-orange-50 px-3 py-1 rounded-full border border-orange-100">
                        Why Kitchen Hug?
                    </span>

                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Everything your recipes need
                    </h2>

                    <p class="mt-3 text-base sm:text-lg text-slate-500 leading-relaxed">
                        Keep your everyday cooking simple, beautifully organized, and enjoyable.
                    </p>

                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">

                    <!-- Card 1 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v14m0-14C10.5 5.7 8.7 5 6.5 5 5.6 5 4.8 5.1 4 5.4v14c.8-.3 1.6-.4 2.5-.4 2.2 0 4 .7 5.5 2m0-14c1.5-1.3 3.3-2 5.5-2 .9 0 1.7.1 2.5.4v14c-.8-.3-1.6-.4-2.5-.4-2.2 0-4 .7-5.5 2"/>
                            </svg>
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Save Your Recipes
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Store your favorite meals digitally so your family traditions
                            are always organized and accessible anywhere.
                        </p>

                    </div>

                    <!-- Card 2 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" stroke-width="1.8"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 16 4.5 4.5"/>
                            </svg>
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Discover New Meals
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Browse curated recipes and discover new ideas for breakfast,
                            dinner, desserts, and quick weeknight meals.
                        </p>

                    </div>

                    <!-- Card 3 -->
                    <div class="group p-8 bg-orange-50/30 border border-orange-100 rounded-3xl hover:bg-white hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                            </svg>
                        </div>

                        <h3 class="mt-6 text-xl font-bold text-slate-900">
                            Cook with Love
                        </h3>

                        <p class="mt-3 text-slate-600 leading-relaxed text-sm">
                            Keep meaningful family recipes close at hand and create customizable 
                            step-by-step guides for cooking success.
                        </p>

                    </div>

                </div>

            </div>
        </section>

        <!-- ================= CTA ================= -->
        <section class="relative bg-orange-100/60 py-20 overflow-hidden">

            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">

                <div class="text-5xl mb-6 inline-block transform hover:scale-110 transition-transform">
                    🥘
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Ready to make something delicious?
                </h2>

                <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl mx-auto">
                    Start building your personal recipe box today and keep every favorite meal organized.
                </p>

                <a href="/register"
                   class="inline-flex items-center gap-2 mt-8 px-8 py-4 bg-orange-500 text-white font-bold text-base rounded-xl shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-orange-500/35 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    Create Your Recipe Box
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

            </div>

        </section>

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="border-t border-orange-100 bg-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('KitchHug_Logo.png') }}" alt="KitchHug — Your Recipe Box" class="h-12 w-auto object-contain">
                </a>
                <p class="text-xs font-medium text-slate-400">
                    &copy; {{ date('Y') }} KitchHug. Made with ❤️ for home cooks.
                </p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            // ── Reactions modal fallback (hero page has no modal) ─────────
            if (typeof window.openReactionsModal === 'undefined') {
                window.openReactionsModal = () => {
                    window.location.href = '{{ route("login") }}';
                };
            }

            // ── Carousel ──────────────────────────────────────────────────
            const carousel = document.querySelector('[data-carousel]');
            if (carousel) {
                const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
                let currentIndex = 0;

                const showSlide = (index) => {
                    currentIndex = (index + slides.length) % slides.length;
                    slides.forEach((slide, slideIndex) => {
                        slide.classList.toggle('hidden', slideIndex !== currentIndex);
                        slide.setAttribute('aria-hidden', String(slideIndex !== currentIndex));
                    });
                };

                showSlide(0);
                window.setInterval(() => showSlide(currentIndex + 1), 4000);
            }

            // ── Community media tabs ───────────────────────────────────────
            document.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof Element)) return;

                const button = target.closest('[data-community-media-tab]');
                if (!(button instanceof HTMLButtonElement)) return;

                const tablist = button.closest('[data-community-media-tabs]');
                if (!(tablist instanceof HTMLElement)) return;

                const selectedMedia = button.dataset.communityMediaTab;

                tablist.querySelectorAll('[data-community-media-tab]').forEach((tab) => {
                    if (!(tab instanceof HTMLButtonElement)) return;
                    const isSelected = tab === button;
                    tab.setAttribute('aria-selected', String(isSelected));
                    tab.tabIndex = isSelected ? 0 : -1;
                    tab.classList.toggle('after:bg-orange-500', isSelected);
                    tab.classList.toggle('text-orange-600', isSelected);
                    tab.classList.toggle('after:bg-transparent', !isSelected);
                    tab.classList.toggle('text-slate-500', !isSelected);
                    tab.classList.toggle('hover:text-slate-800', !isSelected);
                });

                tablist.querySelectorAll('[data-community-media-panel]').forEach((panel) => {
                    if (panel instanceof HTMLElement) {
                        panel.hidden = panel.dataset.communityMediaPanel !== selectedMedia;
                    }
                });
            });

            // ── Share button ───────────────────────────────────────────────
            document.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof Element)) return;

                const shareButton = target.closest('[data-share-recipe]');
                if (!(shareButton instanceof HTMLButtonElement)) return;

                const loginUrl = shareButton.dataset.loginUrl;
                if (loginUrl) { window.location.href = loginUrl; return; }

                const status = shareButton.parentElement?.querySelector('[data-share-status]');
                const url   = shareButton.dataset.shareUrl;
                const title = shareButton.dataset.shareTitle;
                const text  = shareButton.dataset.shareText;
                if (!url || !title || !text) return;

                (async () => {
                    try {
                        if (navigator.share) { await navigator.share({ title, text, url }); return; }
                        if (!navigator.clipboard?.writeText) { if (status) status.textContent = 'Sharing unavailable in this browser.'; return; }
                        await navigator.clipboard.writeText(url);
                        if (status) status.textContent = 'Link copied!';
                    } catch (e) {
                        if (e instanceof DOMException && e.name === 'AbortError') return;
                        if (status) status.textContent = 'Could not share the recipe link.';
                    }
                })();
            });
        })();
    </script>

</body>
</html>