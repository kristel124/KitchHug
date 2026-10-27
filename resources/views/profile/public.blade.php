@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <a href="javascript:history.back()" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Back</a>
                    @if (session('status'))
                        <p role="status" class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
                    @endif
                    <div class="mt-7 flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-5">
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-3xl bg-slate-900 text-2xl font-black text-white shadow-lg">
                                @if ($user->profile_photo_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}" alt="{{ $user->name }} profile photo" class="h-full w-full object-cover">
                                @else
                                    {{ $user->initials() }}
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">Home cook</p>
                                <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $user->name }}</h1>
                                <p class="mt-2 text-sm text-slate-600">Member since {{ $user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                        @if ($isOwnProfile)
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-xl border border-orange-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-orange-50">Manage my profile</a>
                            </div>
                        @elseif (auth()->check())
                            <form method="POST" action="{{ route('users.follow', $user) }}">
                                @csrf
                                <button type="submit" class="inline-flex min-w-32 items-center justify-center rounded-xl px-5 py-3 text-sm font-bold transition {{ $isFollowing ? 'border border-orange-200 bg-white text-slate-700 hover:bg-orange-50' : 'bg-orange-500 text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600' }}">
                                    {{ $isFollowing ? 'Following' : '＋ Follow' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Log in to follow</a>
                        @endif
                    </div>

                    <dl class="mt-8 grid max-w-xl grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-orange-100 bg-white/80 p-4">
                            <dt class="text-xs font-bold uppercase tracking-widest text-slate-400">Recipes shared</dt>
                            <dd class="mt-1 text-2xl font-black text-slate-900">{{ $recipes->count() }}</dd>
                        </div>
                        <button type="button" onclick="openFollowModal({{ $user->id }}, 'followers')"
                                class="group rounded-2xl border border-orange-100 bg-white/80 p-4 text-left transition hover:border-orange-300 hover:bg-white">
                            <dt class="text-xs font-bold uppercase tracking-widest text-slate-400 transition group-hover:text-orange-500">Followers</dt>
                            <dd class="mt-1 text-2xl font-black text-slate-900 transition group-hover:text-orange-600">{{ $followersCount }}</dd>
                        </button>
                        <button type="button" onclick="openFollowModal({{ $user->id }}, 'following')"
                                class="group rounded-2xl border border-orange-100 bg-white/80 p-4 text-left transition hover:border-orange-300 hover:bg-white">
                            <dt class="text-xs font-bold uppercase tracking-widest text-slate-400 transition group-hover:text-orange-500">Following</dt>
                            <dd class="mt-1 text-2xl font-black text-slate-900 transition group-hover:text-orange-600">{{ $followingCount }}</dd>
                        </button>
                    </dl>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="mb-6 border-b border-orange-100 pb-4">
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-orange-500">From {{ $user->name }}’s kitchen</p>
                    <h2 class="mt-1 text-2xl font-black text-slate-900">Shared recipes</h2>
                </div>

                @if ($recipes->isEmpty())
                    <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-12 text-center">
                        <h3 class="text-xl font-black text-slate-900">No recipes shared yet</h3>
                        <p class="mt-2 text-sm text-slate-500">When {{ $user->name }} shares a recipe, it will appear here.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($recipes as $recipe)
                            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg">
                                <a href="{{ route('recipes.show', $recipe) }}" class="group block">
                                    <div class="relative h-48 overflow-hidden bg-slate-100">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->image_path) }}" alt="Recipe photo for {{ $recipe->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                        <span class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-800">{{ $recipe->category }}</span>
                                        @if ($recipe->video_path)
                                            <span class="absolute right-3 top-3 rounded-md bg-slate-950/75 px-2.5 py-1 text-[11px] font-semibold text-white">Video</span>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-5">
                                    <a href="{{ route('recipes.show', $recipe) }}" class="group block">
                                        <h3 class="text-lg font-bold leading-snug text-slate-900 group-hover:text-orange-600">{{ $recipe->title }}</h3>
                                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $recipe->description }}</p>
                                    </a>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @if ($recipe->recipe_type)
                                            <span class="rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600">{{ $recipe->recipe_type }}</span>
                                        @endif
                                        @if ($recipe->method)
                                            <span class="rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-medium text-slate-600">{{ $recipe->method }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                        <span class="text-xs font-medium text-slate-500">{{ $recipe->prep_time_minutes }} min · {{ $recipe->created_at->format('M j, Y') }}</span>
                                        <a href="{{ route('recipes.show', $recipe) }}" class="text-sm font-semibold text-orange-700 hover:text-orange-800">View recipe <span aria-hidden="true">→</span></a>
                                    </div>
                                    @if ($recipe->video_path)
                                        <div class="mt-4 overflow-hidden rounded-xl bg-slate-950">
                                            <video controls preload="metadata" data-recipe-video-player class="max-h-64 w-full" aria-label="Recipe video for {{ $recipe->title }}">
                                                <source src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($recipe->video_path) }}">
                                                Your browser does not support video playback.
                                            </video>
                                        </div>
                                    @endif
                                    @include('partials.recipe-post-actions', ['recipe' => $recipe])
                                    <details class="mt-4 border-t border-slate-100 pt-3">
                                        <summary class="cursor-pointer text-sm font-semibold text-slate-700 hover:text-orange-700">Ingredients and instructions</summary>
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
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>

    @include('profile.partials.follow-modal')
@endsection
