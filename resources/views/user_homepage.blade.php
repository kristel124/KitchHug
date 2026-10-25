@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')
        <main class="flex-1">
            <section data-dish-carousel class="relative isolate overflow-hidden bg-orange-50 py-12 lg:py-16" role="region" aria-label="Dish inspiration" aria-roledescription="carousel">
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
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-orange-50/95 via-orange-50/80 to-orange-50/30"></div>
                <div class="pointer-events-none absolute inset-0 bg-white/10"></div>
                <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-orange-300/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 left-10 h-72 w-72 rounded-full bg-white/60 blur-3xl"></div>
                <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-xs font-bold text-orange-600 shadow-sm">👋 Welcome back, Chef!</div>
                            <h1 class="mt-5 text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">What are we cooking, <span class="text-orange-500">{{ Auth::user()->name ?? 'Chef' }}</span>?</h1>
                            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">Your kitchen starts here. Save your favorite recipes, discover something delicious, and keep everything organized in one place.</p>
                            <div class="mt-7 flex flex-col gap-3 sm:flex-row"><a href="{{ route('recipes.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">＋ Add New Recipe</a><a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-orange-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-orange-50">My Profile <span>→</span></a></div>
                    </div>
                </div>
            </section>
            <div class="mx-auto max-w-7xl space-y-12 px-4 py-10 sm:px-6 lg:px-8">
                <section>
                    <div class="flex items-end justify-between border-b border-orange-100 pb-4">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">Your collection</p>
                            <h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Your Recipe Box</h2>
                        </div>
                    </div>
                    <div class="mt-6 rounded-3xl border border-orange-100 bg-white px-6 py-12 text-center shadow-sm">
                        <div class="text-5xl" aria-hidden="true">🥘</div>
                        <h3 class="mt-4 text-xl font-extrabold text-slate-900">Your recipe box is empty</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">You haven’t added any recipes yet. Add your first recipe and it’ll show up here.</p>
                        <a href="{{ route('recipes.create') }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">＋ Add Your First Recipe</a>
                    </div>
                </section>
                <section class="relative overflow-hidden rounded-3xl bg-slate-900 text-white shadow-lg"><div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-orange-500/20 blur-3xl"></div><div class="relative flex flex-col justify-between gap-6 px-6 py-8 sm:px-10 sm:py-10 md:flex-row md:items-center"><div class="max-w-2xl"><div class="text-xs font-extrabold uppercase tracking-widest text-orange-400">💡 Kitchen Tip</div><h2 class="mt-3 text-2xl font-black sm:text-3xl">Keep your herbs fresh for longer.</h2><p class="mt-3 text-sm leading-7 text-slate-300 sm:text-base">Store fresh herbs in a glass of water and loosely cover them with a reusable bag before placing them in the refrigerator.</p></div><a href="#" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">More Tips →</a></div></section>
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