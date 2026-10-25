@extends('layouts.app')

@section('content')
    @php
        $videos = [
            ['title' => 'Knife Skills: The Basics', 'category' => 'Kitchen Skills', 'duration' => '8 min', 'level' => 'Beginner', 'emoji' => '🔪', 'color' => 'from-amber-100 to-orange-50', 'query' => 'beginner kitchen knife skills tutorial'],
            ['title' => 'Weeknight Creamy Tomato Pasta', 'category' => 'Pasta', 'duration' => '18 min', 'level' => 'Easy', 'emoji' => '🍝', 'color' => 'from-rose-100 to-orange-50', 'query' => 'creamy tomato pasta recipe tutorial'],
            ['title' => 'Golden Crispy Chicken', 'category' => 'Dinner', 'duration' => '24 min', 'level' => 'Easy', 'emoji' => '🍗', 'color' => 'from-orange-100 to-yellow-50', 'query' => 'crispy chicken recipe cooking tutorial'],
            ['title' => 'Fluffy Weekend Pancakes', 'category' => 'Breakfast', 'duration' => '12 min', 'level' => 'Easy', 'emoji' => '🥞', 'color' => 'from-yellow-100 to-amber-50', 'query' => 'fluffy pancakes recipe tutorial'],
            ['title' => 'Build a Better Garden Salad', 'category' => 'Fresh & Easy', 'duration' => '10 min', 'level' => 'Beginner', 'emoji' => '🥗', 'color' => 'from-emerald-100 to-lime-50', 'query' => 'garden salad homemade dressing recipe tutorial'],
            ['title' => 'Chocolate Lava Cakes', 'category' => 'Dessert', 'duration' => '22 min', 'level' => 'Intermediate', 'emoji' => '🍫', 'color' => 'from-rose-100 to-amber-50', 'query' => 'chocolate lava cake recipe tutorial'],
        ];
    @endphp

    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-br from-orange-100 via-amber-50 to-[#FAF6F0]">
                <div class="mx-auto flex max-w-7xl flex-col justify-between gap-8 px-4 py-10 sm:px-6 md:flex-row md:items-end lg:px-8 lg:py-14">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-600">Watch, learn, cook</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Cooking Videos</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">Step-by-step inspiration for your next meal, from quick kitchen skills to something sweet.</p>
                    </div>
                    <div class="flex items-center gap-3 rounded-xl border border-white/80 bg-white/75 px-4 py-3 shadow-sm">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-xl" aria-hidden="true">▶</span>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">{{ count($videos) }} videos to explore</p>
                            <p class="mt-0.5 text-xs text-slate-500">Fresh ideas for every skill level</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div class="mb-6 flex items-end justify-between gap-4 border-b border-orange-100 pb-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">The watch list</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900">Find your next favorite</h2>
                    </div>
                    <span class="hidden text-sm font-semibold text-slate-500 sm:block">Recipes, skills, and sweet finishes</span>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($videos as $video)
                        <article class="group overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <a href="{{ 'https://www.youtube.com/results?search_query='.urlencode($video['query']) }}" target="_blank" rel="noopener noreferrer" aria-label="Find {{ $video['title'] }} videos on YouTube">
                                <div class="relative flex aspect-video items-center justify-center bg-gradient-to-br {{ $video['color'] }}">
                                    <span class="text-7xl transition-transform duration-300 group-hover:scale-110" aria-hidden="true">{{ $video['emoji'] }}</span>
                                    <span class="absolute inset-0 flex items-center justify-center">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-full border border-white/80 bg-slate-900/75 pl-1 text-lg text-white shadow-lg transition-transform group-hover:scale-110" aria-hidden="true">▶</span>
                                    </span>
                                    <span class="absolute bottom-3 right-3 rounded-md bg-slate-900/80 px-2 py-1 text-xs font-bold text-white">{{ $video['duration'] }}</span>
                                </div>
                            </a>
                            <div class="p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-600">{{ $video['category'] }}</span>
                                    <span class="rounded-md bg-slate-50 px-2 py-1 text-[11px] font-semibold text-slate-500">{{ $video['level'] }}</span>
                                </div>
                                <h3 class="mt-2 text-lg font-extrabold leading-snug text-slate-900 transition-colors group-hover:text-orange-600">{{ $video['title'] }}</h3>
                                <a class="mt-4 inline-flex items-center gap-2 border-t border-slate-100 pt-3 text-sm font-bold text-orange-600 hover:text-orange-700" href="{{ 'https://www.youtube.com/results?search_query='.urlencode($video['query']) }}" target="_blank" rel="noopener noreferrer">
                                    Watch on YouTube <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection