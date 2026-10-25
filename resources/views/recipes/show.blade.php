@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
                    <a href="{{ auth()->check() ? route('user_homepage') : route('home') }}" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Back to recipes</a>
                    <div class="mt-8 flex flex-col gap-6 sm:flex-row sm:items-center">
                        @if ($recipe['image_url'])
                            <img src="{{ $recipe['image_url'] }}" alt="Recipe photo for {{ $recipe['title'] }}" class="h-48 w-full rounded-3xl object-cover shadow-sm sm:h-56 sm:w-72">
                        @else
                            <div class="flex h-48 w-full items-center justify-center rounded-3xl bg-gradient-to-br from-orange-100 to-amber-50 text-8xl sm:h-56 sm:w-72" aria-hidden="true">{{ $recipe['emoji'] }}</div>
                        @endif
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">{{ $recipe['category'] }}</p>
                            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $recipe['title'] }}</h1>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">{{ $recipe['description'] }}</p>
                            <p class="mt-4 text-sm font-bold text-slate-500">⏱ {{ $recipe['prep_time_minutes'] }} minutes</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
                <aside class="h-fit rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
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

                <div class="space-y-6 lg:col-span-2">
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
                </div>
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
