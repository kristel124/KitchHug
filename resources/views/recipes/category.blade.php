@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <a href="{{ route('user_homepage') }}" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Home</a>
                    <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Find your next favorite</p>
                    <h1 class="mt-2 text-3xl font-black text-slate-900 sm:text-4xl">{{ $title }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">Browse simple, satisfying {{ strtolower($title) }} ideas for your table.</p>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-end justify-between gap-4 border-b border-orange-100 pb-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">Recipe collection</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900">{{ count($recipes) }} recipes</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recipes as $recipe)
                        <article class="group overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="relative flex h-48 items-center justify-center bg-gradient-to-br {{ $recipe['color'] }} text-7xl">
                                {{ $recipe['emoji'] }}
                                <span class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm">⏱ {{ $recipe['time'] }}</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $title }}</span>
                                    <span class="rounded-md bg-slate-50 px-2 py-1 text-xs font-bold text-slate-600">⭐ {{ $recipe['rating'] }}</span>
                                </div>
                                <h3 class="mt-3 text-xl font-extrabold text-slate-900 group-hover:text-orange-500">{{ $recipe['name'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $recipe['description'] }}</p>
                                <div class="mt-5 border-t border-slate-100 pt-4">
                                    <span class="text-xs font-semibold text-slate-400">Easy to make</span>
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