@extends('layouts.app')

@section('content')
    <div class="flex min-h-dvh flex-col bg-[#FAF6F0] text-slate-800 antialiased lg:h-dvh lg:min-h-0 lg:overflow-hidden">
        @include('partials.kitchhug_header')

        <main class="lg:fixed lg:inset-x-0 lg:bottom-0 lg:top-[76px] lg:z-0 lg:overflow-hidden">
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0] lg:h-full lg:overflow-hidden">
                <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-6 px-4 py-6 sm:px-6 lg:h-full lg:min-h-0 lg:grid-cols-2 lg:px-8">
                    <div id="recipe-scroll-area" class="min-w-0 lg:col-span-1 lg:min-h-0 lg:overflow-y-auto lg:overscroll-contain lg:pr-2">
                        <a href="{{ $recipe['back_url'] ?? (auth()->check() ? route('user_homepage') : route('home')) }}" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Back to recipes</a>
                        <div class="mt-6 flex flex-col gap-6 sm:flex-row sm:items-center">
                            @if ($recipe['image_url'])
                                <img src="{{ $recipe['image_url'] }}" alt="Recipe photo for {{ $recipe['title'] }}" class="h-48 w-full rounded-3xl object-cover shadow-sm sm:h-56 sm:w-72">
                            @else
                                <div class="flex h-48 w-full items-center justify-center rounded-3xl bg-gradient-to-br from-orange-100 to-amber-50 text-8xl sm:h-56 sm:w-72" aria-hidden="true">{{ $recipe['emoji'] }}</div>
                            @endif
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">{{ $recipe['category'] }}</p>
                                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $recipe['title'] }}</h1>
                                @if (isset($recipe['author_id']))
                                    <p class="mt-2 text-sm font-semibold text-slate-500">Shared by <a href="{{ route('users.show', $recipe['author_id']) }}" class="text-orange-600 transition hover:text-orange-700 hover:underline">{{ $recipe['author'] }}</a></p>
                                @else
                                    <p class="mt-2 text-sm font-semibold text-slate-500">Shared by {{ $recipe['author'] ?? 'KitchHug Kitchen' }}</p>
                                @endif
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">{{ $recipe['description'] }}</p>
                                <p class="mt-4 text-sm font-bold text-slate-500">⏱ {{ $recipe['prep_time'] ?? ($recipe['prep_time_minutes'].' minutes') }}</p>
                                @if ($recipe['is_curated'] ?? false)
                                    @auth
                                        <form method="POST" action="{{ route('favorites.toggle') }}" class="mt-5">
                                            @csrf
                                            <input type="hidden" name="category" value="{{ $recipe['category_slug'] }}">
                                            <input type="hidden" name="recipe_slug" value="{{ $recipe['recipe_slug'] }}">
                                            <button type="submit" class="rounded-xl px-5 py-3 text-sm font-bold transition {{ $recipe['is_favorite'] ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-orange-500 text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600' }}">
                                                {{ $recipe['is_favorite'] ? '♥ Remove from Favorites' : '♡ Save to Favorites' }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="mt-5 inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">♡ Log in to Save</a>
                                    @endauth
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 space-y-6 pb-6">
                            @if ($recipe['ingredients'] && $recipe['instructions'])
                                <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                    <h2 class="text-xl font-black text-slate-900">Ingredients</h2>
                                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $recipe['ingredients'] }}</p>
                                </div>
                                <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                    <h2 class="text-xl font-black text-slate-900">Instructions</h2>
                                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $recipe['instructions'] }}</p>
                                    @if ($recipe['video_url'])
                                        <video controls preload="metadata" class="mt-6 max-h-80 w-full rounded-2xl bg-slate-900" aria-label="Recipe video for {{ $recipe['title'] }}">
                                            <source src="{{ $recipe['video_url'] }}">
                                            Your browser does not support video playback.
                                        </video>
                                    @endif
                                </div>
                            @else
                                <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                    <h2 class="text-xl font-black text-slate-900">About this recipe</h2>
                                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $recipe['description'] }}</p>
                                    <p class="mt-4 rounded-xl bg-orange-50 px-4 py-3 text-sm leading-6 text-orange-800">Ingredients and step-by-step instructions for this featured recipe are not available yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <aside class="h-fit min-w-0 rounded-3xl border border-orange-100 bg-white p-5 shadow-sm lg:col-span-1 lg:self-start">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-xl font-black text-slate-900">Comments</h2>
                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-600">{{ $comments->count() }}</span>
                        </div>
                        @if (session('status'))
                            <p role="status" class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
                        @endif
                        @auth
                            <form method="POST" action="{{ $recipe['comment_action'] }}" class="mt-5">
                                @csrf
                                <label for="body" class="text-sm font-bold text-slate-700">Add your comment</label>
                                <textarea id="body" name="body" rows="4" maxlength="2000" required class="mt-2 w-full rounded-xl border border-orange-100 bg-[#FFFCF8] px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100" placeholder="Share what you think about this recipe...">{{ old('body') }}</textarea>
                                @error('body')
                                    <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="mt-3 w-full rounded-xl bg-orange-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-orange-600">Post comment</button>
                            </form>
                        @else
                            <p class="mt-5 rounded-2xl bg-orange-50 p-4 text-sm leading-6 text-slate-600">
                                Want to share your thoughts?
                                <a href="{{ route('login') }}" class="font-bold text-orange-600 transition hover:text-orange-700">Log in</a>
                                or
                                <a href="{{ route('register') }}" class="font-bold text-orange-600 transition hover:text-orange-700">create an account</a>
                                to comment.
                            </p>
                        @endauth

                        <div class="mt-6 space-y-4 border-t border-orange-100 pt-5">
                            @forelse ($comments as $comment)
                                <article class="rounded-2xl bg-[#FFFCF8] p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="text-sm font-extrabold text-slate-800">{{ $comment->user->name }}</h3>
                                        <time class="text-xs font-medium text-slate-400" datetime="{{ $comment->created_at->toISOString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                                    </div>
                                    <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-slate-600">{{ $comment->body }}</p>
                                </article>
                            @empty
                                <p class="text-sm leading-6 text-slate-500">No comments yet. Be the first to share your thoughts.</p>
                            @endforelse
                        </div>
                    </aside>
                </div>
            </section>
        </main>

        <div class="lg:hidden">
            @include('partials.kitchhug_footer')
        </div>
    </div>
@endsection
