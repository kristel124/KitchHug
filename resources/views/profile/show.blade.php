@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-5">
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-slate-900 text-2xl font-black text-white shadow-lg">
                                {{ $user->initials() }}
                            </div>
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Your kitchen</p>
                                <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $user->name }}</h1>
                                <p class="mt-2 text-sm text-slate-600">Member since {{ $user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-orange-50">Edit account</a>
                            <a href="{{ route('recipes.create') }}" class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">＋ Share a recipe</a>
                        </div>
                    </div>

                    <dl class="mt-8 grid max-w-xl grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-orange-100 bg-white/80 p-4">
                            <dt class="text-xs font-bold uppercase tracking-widest text-slate-400">Recipes shared</dt>
                            <dd class="mt-1 text-2xl font-black text-slate-900">{{ $recipes->count() }}</dd>
                        </div>
                        <div class="rounded-2xl border border-orange-100 bg-white/80 p-4">
                            <dt class="text-xs font-bold uppercase tracking-widest text-slate-400">Account</dt>
                            <dd class="mt-1 truncate text-sm font-bold text-slate-900">{{ $user->email }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif

                <div class="mb-6 flex items-end justify-between gap-4 border-b border-orange-100 pb-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">From your kitchen</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900">Your recipes</h2>
                    </div>
                    <span class="text-sm font-semibold text-slate-500">{{ $recipes->count() }} {{ \Illuminate\Support\Str::plural('recipe', $recipes->count()) }}</span>
                </div>

                @if ($recipes->isEmpty())
                    <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-14 text-center">
                        <div class="text-5xl" aria-hidden="true">🥣</div>
                        <h3 class="mt-4 text-xl font-black text-slate-900">Your recipe collection starts here</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Share a favorite dish with the KitchHug community. Add the ingredients and steps so other home cooks can make it too.</p>
                        <a href="{{ route('recipes.create') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">＋ Post your first recipe</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach ($recipes as $recipe)
                            <article class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="mb-5 h-56 w-full rounded-2xl object-cover">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">{{ $recipe->category }}</span>
                                        <h3 class="mt-2 text-xl font-extrabold text-slate-900"><a href="{{ route('recipes.show', $recipe) }}" class="transition hover:text-orange-500">{{ $recipe->title }}</a></h3>
                                        <p class="mt-1 text-xs font-semibold text-slate-400">⏱ {{ $recipe->prep_time_minutes }} min · Shared {{ $recipe->created_at->format('M j, Y') }}</p>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-2">
                                        <a href="{{ route('recipes.edit', $recipe) }}" class="rounded-lg border border-orange-100 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-orange-50 hover:text-orange-600">Edit</a>
                                        <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" onsubmit="return confirm('Delete this recipe? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-100 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <p class="mt-4 text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                @if ($recipe->video_path)
                                    <video controls preload="metadata" class="mt-4 max-h-72 w-full rounded-2xl bg-slate-900" aria-label="Recipe video for {{ $recipe->title }}">
                                        <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->video_path) }}">
                                        Your browser does not support video playback.
                                    </video>
                                @endif
                                <details class="mt-4 border-t border-slate-100 pt-4">
                                    <summary class="cursor-pointer text-sm font-bold text-orange-600">Ingredients and instructions</summary>
                                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Ingredients</h4>
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $recipe->ingredients }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Instructions</h4>
                                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $recipe->instructions }}</p>
                                        </div>
                                    </div>
                                </details>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
