@extends('layouts.app')

@section('content')
    <div class="flex min-h-dvh flex-col bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

                <a href="{{ $recipe['back_url'] ?? (auth()->check() ? route('user_homepage') : route('home')) }}" class="mb-4 inline-flex items-center gap-1 text-sm font-bold text-orange-600 transition hover:text-orange-700">
                    <svg viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 19l-7-7 7-7"/></svg>
                    Back to recipes
                </a>

                <div class="flex flex-col gap-6 lg:flex-row">

                    {{-- ───────────── LEFT COLUMN ───────────── --}}
                    <div class="min-w-0 flex-1">

                        {{-- Video player (only shown on left if video exists) --}}
                        @if ($recipe['video_url'])
                            <div class="overflow-hidden rounded-2xl bg-slate-950 shadow-lg">
                                <video controls preload="metadata" data-recipe-video-player class="aspect-video w-full" aria-label="Recipe video for {{ $recipe['title'] }}">
                                    <source src="{{ $recipe['video_url'] }}">
                                    Your browser does not support video playback.
                                </video>
                            </div>
                        @endif

                        {{-- Title & meta --}}
                        <div class="mt-4">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">{{ $recipe['category'] }}</p>
                            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $recipe['title'] }}</h1>

                            <div class="mt-1 flex flex-nowrap items-center gap-x-2 overflow-hidden text-sm text-slate-500">
                                @if (($recipe['method'] ?? null) || ($recipe['recipe_type'] ?? null))
                                    <span class="font-semibold">
                                        @if ($recipe['recipe_type'] ?? null){{ $recipe['recipe_type'] }}@endif
                                        @if (($recipe['recipe_type'] ?? null) && ($recipe['method'] ?? null)) · @endif
                                        @if ($recipe['method'] ?? null){{ $recipe['method'] }}@endif
                                    </span>
                                    <span class="text-slate-300">·</span>
                                @endif
                                <span>⏱ {{ $recipe['prep_time'] ?? ($recipe['prep_time_minutes'].' min') }}</span>
                            </div>

                            {{-- Author --}}
                            <div class="mt-3 flex items-center gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-black text-orange-700">
                                    {{ strtoupper(substr($recipe['author'] ?? '?', 0, 1)) }}
                                </span>
                                <div>
                                    @if (isset($recipe['author_id']))
                                        <a href="{{ route('users.show', $recipe['author_id']) }}" class="text-sm font-bold text-slate-900 transition hover:text-orange-600">{{ $recipe['author'] }}</a>
                                    @else
                                        <span class="text-sm font-bold text-slate-900">{{ $recipe['author'] ?? 'KitchHug Kitchen' }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            @if ($recipe['description'])
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $recipe['description'] }}</p>
                            @endif
                        </div>

                        {{-- Action bar --}}
                        <div class="mt-4 flex flex-wrap items-center gap-2 border-b border-t border-orange-100 py-3">
                            @if (isset($recipe['id']))
                                @auth
                                    <form method="POST" action="{{ route('recipes.reactions.toggle', $recipe['id']) }}" data-heart-form>
                                        @csrf
                                        <button type="submit" aria-label="{{ $recipe['is_liked'] ? 'Remove heart reaction' : 'React with a heart' }}" aria-pressed="{{ $recipe['is_liked'] ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $recipe['is_liked'] ? 'border border-rose-200 bg-rose-100 text-rose-700 hover:bg-rose-200' : 'border border-orange-100 bg-white text-slate-700 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700' }}">
                                            @if ($recipe['is_liked'])
                                                <svg aria-hidden="true" viewBox="0 0 32 32" fill="currentColor" class="size-4"><path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/></svg>
                                            @else
                                                <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" class="size-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/></svg>
                                            @endif
                                            {{ $recipe['reaction_count'] }} {{ \Illuminate\Support\Str::plural('heart', $recipe['reaction_count']) }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-orange-100 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-rose-50 hover:text-rose-700">
                                        <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" class="size-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/></svg>
                                        {{ $recipe['reaction_count'] }} {{ \Illuminate\Support\Str::plural('heart', $recipe['reaction_count']) }}
                                    </a>
                                @endauth
                            @endif

                            <a href="#comments" onclick="document.getElementById('comments').scrollIntoView({behavior:'smooth'});return false;" class="inline-flex items-center gap-2 rounded-xl border border-orange-100 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-orange-50 hover:text-orange-700">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.25 6.75h7.5M8.25 9.75h4.5M5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v8.25a2.25 2.25 0 0 1-2.25 2.25H8.25L3.75 21v-4.5A2.25 2.25 0 0 1 3 14.25V6a2.25 2.25 0 0 1 2.25-2.25Z"/></svg>
                                {{ $comments->count() }} {{ \Illuminate\Support\Str::plural('comment', $comments->count()) }}
                            </a>

                            <div class="flex items-center gap-1">
                                <button type="button" data-share-recipe data-share-url="{{ $recipe['share_url'] }}" data-share-title="{{ $recipe['title'] }}" data-share-text="{{ $recipe['description'] }}" class="inline-flex items-center gap-2 rounded-xl border border-orange-100 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-orange-50 hover:text-orange-700">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-4"><path d="M21.707 11.293 13.414 3l-1.414 1.414 6.293 6.293H3v2h15.293l-6.293 6.293 1.414 1.414 8.293-8.121Z"/></svg>
                                    Share
                                </button>
                                <span data-share-status role="status" aria-live="polite" class="sr-only"></span>
                            </div>

                            @if (($recipe['is_curated'] ?? false) || isset($recipe['id']))
                                @auth
                                    <div class="flex items-center gap-2" data-save-wrapper>
                                        <form method="POST" action="{{ route('favorites.toggle') }}" data-save-form>
                                            @csrf
                                            @if ($recipe['is_curated'] ?? false)
                                                <input type="hidden" name="category" value="{{ $recipe['category_slug'] }}">
                                                <input type="hidden" name="recipe_slug" value="{{ $recipe['recipe_slug'] }}">
                                            @else
                                                <input type="hidden" name="recipe_id" value="{{ $recipe['id'] }}">
                                            @endif
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $recipe['is_favorite'] ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-orange-500 text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600' }}">
                                                @if ($recipe['is_favorite'])
                                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-4"><path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/></svg>
                                                    Saved
                                                @else
                                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/></svg>
                                                    Save
                                                @endif
                                            </button>
                                        </form>
                                        @if (isset($recipe['favorite_count']))
                                            <span class="text-sm text-slate-500" data-save-count>{{ $recipe['favorite_count'] }} {{ \Illuminate\Support\Str::plural('save', $recipe['favorite_count']) }}</span>
                                        @endif
                                    </div>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/></svg>
                                        Log in to Save
                                    </a>
                                @endauth
                            @endif
                        </div>

                        {{-- Comments --}}
                        <section id="comments" class="mt-6 pb-16">
                            <h2 class="text-lg font-black text-slate-900">{{ $comments->count() }} {{ \Illuminate\Support\Str::plural('Comment', $comments->count()) }}</h2>

                            @auth
                                <form method="POST" action="{{ $recipe['comment_action'] }}" class="mt-4 flex gap-3">
                                    @csrf
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-black text-orange-700">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <div class="flex-1">
                                        <textarea id="body" name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100" placeholder="Add a comment...">{{ old('body') }}</textarea>
                                        @error('body')
                                            <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p>
                                        @enderror
                                        <div class="mt-2 flex justify-end gap-2">
                                            <button type="submit" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-600">Comment</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <p class="mt-4 text-sm leading-6 text-slate-600">
                                    <a href="{{ route('login') }}" class="font-bold text-orange-600 transition hover:text-orange-700">Log in</a> or
                                    <a href="{{ route('register') }}" class="font-bold text-orange-600 transition hover:text-orange-700">create an account</a> to comment.
                                </p>
                            @endauth

                            <div class="mt-6 space-y-5">
                                @forelse ($comments as $comment)
                                    <article class="flex gap-3" x-data="{ editing: false, replying: false }">
                                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-black text-orange-700">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </span>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-slate-900">{{ $comment->user->name }}</span>
                                                <time class="text-xs text-slate-400" datetime="{{ $comment->created_at->toISOString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                                            </div>

                                            <div x-show="!editing" class="mt-1">
                                                <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-700">{{ $comment->body }}</p>
                                            </div>
                                            <form x-show="editing" method="POST" action="{{ route('recipes.comments.update', [$recipe['id'], $comment->id]) }}" class="mt-1" x-cloak>
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-3 py-2 text-sm leading-6 text-slate-700 outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ $comment->body }}</textarea>
                                                <div class="mt-1.5 flex gap-2">
                                                    <button type="submit" class="rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-orange-600">Save</button>
                                                    <button type="button" @click="editing = false" class="rounded-lg border border-orange-100 px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                </div>
                                            </form>

                                            <div class="mt-2 flex items-center gap-3">
                                                @auth
                                                    <button type="button" @click="replying = !replying; editing = false" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400 transition hover:text-orange-600">
                                                        <svg viewBox="0 0 24 24" fill="none" class="size-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 15 3 9l6-6M3 9h13a5 5 0 0 1 0 10h-1"/></svg>
                                                        Reply
                                                    </button>
                                                    @if (auth()->id() === $comment->user_id)
                                                        <button type="button" @click="editing = !editing; replying = false" class="text-xs font-semibold text-slate-400 transition hover:text-orange-600">Edit</button>
                                                        <form method="POST" action="{{ route('recipes.comments.destroy', [$recipe['id'], $comment->id]) }}" onsubmit="return confirm('Delete this comment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs font-semibold text-slate-400 transition hover:text-rose-600">Delete</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                                @if ($comment->replies->count() > 0)
                                                    <span class="text-xs text-slate-400">{{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}</span>
                                                @endif
                                            </div>

                                            @auth
                                                <form x-show="replying" method="POST" action="{{ route('recipes.comments.reply', [$recipe['id'], $comment->id]) }}" class="mt-3" x-cloak>
                                                    @csrf
                                                    <textarea name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-3 py-2 text-sm leading-6 text-slate-700 outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100" placeholder="Write a reply..."></textarea>
                                                    <div class="mt-1.5 flex gap-2">
                                                        <button type="submit" class="rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-orange-600">Post reply</button>
                                                        <button type="button" @click="replying = false" class="rounded-lg border border-orange-100 px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                    </div>
                                                </form>
                                            @endauth

                                            @if ($comment->replies->count() > 0)
                                                <div class="mt-4 space-y-4 border-l-2 border-orange-100 pl-4">
                                                    @foreach ($comment->replies as $reply)
                                                        <article class="flex gap-3" x-data="{ editing: false }">
                                                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">
                                                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                            </span>
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-sm font-bold text-slate-900">{{ $reply->user->name }}</span>
                                                                    <time class="text-xs text-slate-400" datetime="{{ $reply->created_at->toISOString() }}">{{ $reply->created_at->diffForHumans() }}</time>
                                                                </div>
                                                                <div x-show="!editing" class="mt-1">
                                                                    <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-700">{{ $reply->body }}</p>
                                                                </div>
                                                                <form x-show="editing" method="POST" action="{{ route('recipes.comments.update', [$recipe['id'], $reply->id]) }}" class="mt-1" x-cloak>
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <textarea name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-3 py-2 text-sm leading-6 text-slate-700 outline-none focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ $reply->body }}</textarea>
                                                                    <div class="mt-1.5 flex gap-2">
                                                                        <button type="submit" class="rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-orange-600">Save</button>
                                                                        <button type="button" @click="editing = false" class="rounded-lg border border-orange-100 px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                                    </div>
                                                                </form>
                                                                @auth
                                                                    @if (auth()->id() === $reply->user_id)
                                                                        <div class="mt-1.5 flex gap-3">
                                                                            <button type="button" @click="editing = !editing" class="text-xs font-semibold text-slate-400 transition hover:text-orange-600">Edit</button>
                                                                            <form method="POST" action="{{ route('recipes.comments.destroy', [$recipe['id'], $reply->id]) }}" onsubmit="return confirm('Delete this reply?')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="text-xs font-semibold text-slate-400 transition hover:text-rose-600">Delete</button>
                                                                            </form>
                                                                        </div>
                                                                    @endif
                                                                @endauth
                                                            </div>
                                                        </article>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <p class="text-sm text-slate-500">No comments yet. Be the first to share your thoughts.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    {{-- ───────────── RIGHT SIDEBAR ───────────── --}}
                    <aside class="w-full shrink-0 lg:w-96 lg:self-start">

                        {{-- Photo (always in sidebar when present) --}}
                        @if ($recipe['image_url'])
                            <div class="overflow-hidden rounded-2xl shadow-sm">
                                <img src="{{ $recipe['image_url'] }}" alt="Recipe photo for {{ $recipe['title'] }}" class="h-64 w-full object-cover">
                            </div>
                        @elseif (! $recipe['video_url'])
                            <div class="flex h-48 w-full items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-8xl shadow-sm" aria-hidden="true">{{ $recipe['emoji'] }}</div>
                        @endif

                        {{-- Recipe title + quick meta in sidebar --}}
                        <div class="mt-4 rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                            <h2 class="text-lg font-black text-slate-900">{{ $recipe['title'] }}</h2>
                            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-4 text-orange-400" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/></svg>
                                    <span class="font-semibold text-slate-700">{{ $recipe['prep_time'] ?? ($recipe['prep_time_minutes'].' min') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-4 text-orange-400" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z"/></svg>
                                    <span class="font-semibold text-slate-700">{{ ucfirst($recipe['category']) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Ingredients --}}
                        @if ($recipe['ingredients'])
                            <div class="mt-4 rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                                <h3 class="text-base font-black text-slate-900">Ingredients</h3>
                                <ul class="mt-3 space-y-2 pr-1">
                                    @foreach (array_filter(preg_split('/\r?\n/', $recipe['ingredients'])) as $ingredient)
                                        <li class="flex items-start gap-2 text-sm text-slate-700">
                                            <span class="mt-2 size-1.5 shrink-0 rounded-full bg-orange-400"></span>
                                            {{ $ingredient }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Instructions --}}
                        @if ($recipe['instructions'])
                            <div class="mt-4 rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                                <h3 class="text-base font-black text-slate-900">Instructions</h3>
                                <ol class="mt-3 space-y-3">
                                    @foreach (array_filter(preg_split('/\r?\n/', $recipe['instructions'])) as $index => $step)
                                        <li class="flex items-start gap-3 text-sm text-slate-700">
                                            <span class="flex size-5 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">{{ $index + 1 }}</span>
                                            <span class="leading-6">{{ $step }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        @if (! $recipe['ingredients'] && ! $recipe['instructions'])
                            <div class="mt-4 rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                                <p class="text-sm text-slate-600">{{ $recipe['description'] }}</p>
                                <p class="mt-3 rounded-xl bg-orange-50 px-4 py-3 text-sm leading-6 text-orange-800">Ingredients and step-by-step instructions for this featured recipe are not available yet.</p>
                            </div>
                        @endif

                    </aside>

                </div>
            </div>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
