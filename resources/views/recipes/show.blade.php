@extends('layouts.app')

@section('content')
    <div class="flex min-h-dvh flex-col bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main>
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                    <div id="recipe-scroll-area" class="min-w-0">
                        <a href="{{ $recipe['back_url'] ?? (auth()->check() ? route('user_homepage') : route('home')) }}" class="text-sm font-bold text-orange-600 transition hover:text-orange-700">← Back to recipes</a>
                        <div class="mt-6">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-500">{{ $recipe['category'] }}</p>
                                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">{{ $recipe['title'] }}</h1>
                                @if (($recipe['method'] ?? null) || ($recipe['recipe_type'] ?? null))
                                    <p class="mt-2 text-xs font-bold uppercase tracking-wide text-slate-500">
                                        @if ($recipe['recipe_type'] ?? null) {{ $recipe['recipe_type'] }} @endif
                                        @if (($recipe['recipe_type'] ?? null) && ($recipe['method'] ?? null)) · @endif
                                        @if ($recipe['method'] ?? null) {{ $recipe['method'] }} @endif
                                    </p>
                                @endif
                                @if (isset($recipe['author_id']))
                                    <p class="mt-2 text-sm font-semibold text-slate-500">Shared by <a href="{{ route('users.show', $recipe['author_id']) }}" class="text-orange-600 transition hover:text-orange-700 hover:underline">{{ $recipe['author'] }}</a></p>
                                @else
                                    <p class="mt-2 text-sm font-semibold text-slate-500">Shared by {{ $recipe['author'] ?? 'KitchHug Kitchen' }}</p>
                                @endif
                                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">{{ $recipe['description'] }}</p>
                                <p class="mt-4 text-sm font-bold text-slate-500">⏱ {{ $recipe['prep_time'] ?? ($recipe['prep_time_minutes'].' minutes') }}</p>
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    @if (isset($recipe['id']))
                                        @auth
                                            {{-- Toggle heart reaction (all authenticated users, including owner) --}}
                                            <form method="POST" action="{{ route('recipes.reactions.toggle', $recipe['id']) }}">
                                                @csrf
                                                <button type="submit" aria-label="{{ $recipe['is_liked'] ? 'Remove heart reaction' : 'React with a heart' }}" aria-pressed="{{ $recipe['is_liked'] ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition {{ $recipe['is_liked'] ? 'border border-rose-200 bg-rose-100 text-rose-700 hover:bg-rose-200' : 'border border-orange-100 bg-white text-slate-700 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700' }}">
                                                    @if ($recipe['is_liked'])
                                                        {{-- Filled heart when liked --}}
                                                        <svg aria-hidden="true" viewBox="0 0 32 32" fill="currentColor" class="size-5">
                                                            <path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/>
                                                        </svg>
                                                    @else
                                                        {{-- Outline heart --}}
                                                        <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" class="size-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/>
                                                        </svg>
                                                    @endif
                                                    <span>{{ $recipe['reaction_count'] }} {{ \Illuminate\Support\Str::plural('heart', $recipe['reaction_count']) }}</span>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Guest: link to login to react --}}
                                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-rose-50 hover:text-rose-700">
                                                {{-- Outline heart --}}
                                                <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" class="size-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M16 28S4 20.5 4 11.5A7.5 7.5 0 0 1 16 6.257 7.5 7.5 0 0 1 28 11.5C28 20.5 16 28 16 28Z"/>
                                                </svg>
                                                <span>{{ $recipe['reaction_count'] }} {{ \Illuminate\Support\Str::plural('heart', $recipe['reaction_count']) }} · Log in to react</span>
                                            </a>
                                        @endauth
                                    @endif
                                    {{-- Share post button — forward arrow icon --}}
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-share-recipe data-share-url="{{ $recipe['share_url'] }}" data-share-title="{{ $recipe['title'] }}" data-share-text="{{ $recipe['description'] }}" class="inline-flex items-center gap-2 rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-700">
                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                                <path d="M21.707 11.293 13.414 3l-1.414 1.414 6.293 6.293H3v2h15.293l-6.293 6.293 1.414 1.414 8.293-8.121Z"/>
                                            </svg>
                                            Share post
                                        </button>
                                        <span data-share-status role="status" aria-live="polite" class="text-xs text-slate-500"></span>
                                    </div>
                                    {{-- Comment button — smooth scrolls to the comments section below --}}
                                    <a href="#comments" onclick="document.getElementById('comments').scrollIntoView({behavior:'smooth'});return false;" class="inline-flex items-center gap-2 rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-700">
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M8.25 6.75h7.5M8.25 9.75h4.5M5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v8.25a2.25 2.25 0 0 1-2.25 2.25H8.25L3.75 21v-4.5A2.25 2.25 0 0 1 3 14.25V6a2.25 2.25 0 0 1 2.25-2.25Z"/>
                                        </svg>
                                        Comment
                                    </a>
                                    @if (($recipe['is_curated'] ?? false) || isset($recipe['id']))
                                        @auth
                                            {{-- Save button + count grouped so they never split across lines --}}
                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('favorites.toggle') }}">
                                                    @csrf
                                                    @if ($recipe['is_curated'] ?? false)
                                                        <input type="hidden" name="category" value="{{ $recipe['category_slug'] }}">
                                                        <input type="hidden" name="recipe_slug" value="{{ $recipe['recipe_slug'] }}">
                                                    @else
                                                        <input type="hidden" name="recipe_id" value="{{ $recipe['id'] }}">
                                                    @endif
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition {{ $recipe['is_favorite'] ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-orange-500 text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600' }}">
                                                        @if ($recipe['is_favorite'])
                                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                                                <path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/>
                                                            </svg>
                                                            Saved
                                                        @else
                                                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/>
                                                            </svg>
                                                            Save Recipe
                                                        @endif
                                                    </button>
                                                </form>
                                                @if (isset($recipe['favorite_count']))
                                                    <span class="text-sm text-slate-500">{{ $recipe['favorite_count'] }} {{ \Illuminate\Support\Str::plural('save', $recipe['favorite_count']) }}</span>
                                                @endif
                                            </div>
                                        @else
                                            {{-- Guest: link to login to save --}}
                                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">
                                                {{-- Outline bookmark --}}
                                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5 2a2 2 0 0 0-2 2v18l9-4 9 4V4a2 2 0 0 0-2-2H5Z"/>
                                                </svg>
                                                Log in to Save
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>

                            @if ($recipe['image_url'] || $recipe['video_url'])
                                <div class="mt-6 grid gap-5 {{ $recipe['image_url'] && $recipe['video_url'] ? 'md:grid-cols-2' : 'grid-cols-1' }}">
                                    @if ($recipe['image_url'])
                                        <section aria-labelledby="recipe-photo-heading" class="overflow-hidden rounded-3xl border border-orange-100 bg-white p-4 shadow-sm sm:p-5">
                                            <h2 id="recipe-photo-heading" class="text-base font-bold text-slate-900">Recipe photo</h2>
                                            <img src="{{ $recipe['image_url'] }}" alt="Recipe photo for {{ $recipe['title'] }}" class="mt-3 max-h-[28rem] w-full rounded-2xl bg-slate-100 object-contain">
                                        </section>
                                    @endif

                                    @if ($recipe['video_url'])
                                        <section aria-labelledby="recipe-video-heading" class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                            <h2 id="recipe-video-heading" class="text-base font-bold text-slate-900">Recipe video</h2>
                                            <div class="mt-3 overflow-hidden rounded-2xl bg-slate-950">
                                                <video controls preload="metadata" data-recipe-video-player class="aspect-video w-full" aria-label="Recipe video for {{ $recipe['title'] }}">
                                                    <source src="{{ $recipe['video_url'] }}">
                                                    Your browser does not support video playback.
                                                </video>
                                            </div>
                                        </section>
                                    @endif
                                </div>
                            @elseif (! $recipe['image_url'])
                                <div class="mt-6 flex h-48 w-full items-center justify-center rounded-3xl bg-gradient-to-br from-orange-100 to-amber-50 text-8xl sm:h-56" aria-hidden="true">{{ $recipe['emoji'] }}</div>
                            @endif
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
                                </div>
                            @else
                                <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                    <h2 class="text-xl font-black text-slate-900">About this recipe</h2>
                                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $recipe['description'] }}</p>
                                    <p class="mt-4 rounded-xl bg-orange-50 px-4 py-3 text-sm leading-6 text-orange-800">Ingredients and step-by-step instructions for this featured recipe are not available yet.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Comments section --}}
                        <section id="comments" class="mt-10 pb-16">
                            <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                {{-- Header: title + count badge --}}
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-black text-slate-900">Comments</h2>
                                    <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-600">{{ $comments->count() }}</span>
                                </div>

                                {{-- Post a comment --}}
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
                                    <p class="mt-5 text-sm leading-6 text-slate-600">
                                        Want to share your thoughts?
                                        <a href="{{ route('login') }}" class="font-bold text-orange-600 transition hover:text-orange-700">Log in</a>
                                        or
                                        <a href="{{ route('register') }}" class="font-bold text-orange-600 transition hover:text-orange-700">create an account</a>
                                        to comment.
                                    </p>
                                @endauth

                                {{-- Divider --}}
                                <div class="mt-6 border-t border-orange-100"></div>

                                {{-- Comment list --}}
                                <div class="mt-5 space-y-4">
                                    @forelse ($comments as $comment)
                                        <article class="rounded-2xl bg-[#FFFCF8] p-4" x-data="{ editing: false, replying: false }">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">
                                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                    </span>
                                                    <div>
                                                        <h3 class="text-sm font-extrabold text-slate-800">{{ $comment->user->name }}</h3>
                                                        <time class="text-xs font-medium text-slate-400" datetime="{{ $comment->created_at->toISOString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                                @auth
                                                    @if (auth()->id() === $comment->user_id)
                                                        {{-- Edit / Delete actions for comment owner --}}
                                                        <div class="flex items-center gap-2">
                                                            <button type="button" @click="editing = !editing; replying = false" class="text-xs font-semibold text-slate-400 transition hover:text-orange-600">Edit</button>
                                                            <form method="POST" action="{{ route('recipes.comments.destroy', [$recipe['id'], $comment->id]) }}" onsubmit="return confirm('Delete this comment?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-xs font-semibold text-slate-400 transition hover:text-rose-600">Delete</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>

                                            {{-- Comment body / inline edit form --}}
                                            <div x-show="!editing" class="mt-2">
                                                <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-600">{{ $comment->body }}</p>
                                            </div>
                                            <form x-show="editing" method="POST" action="{{ route('recipes.comments.update', [$recipe['id'], $comment->id]) }}" class="mt-2" x-cloak>
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="body" rows="3" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ $comment->body }}</textarea>
                                                <div class="mt-2 flex gap-2">
                                                    <button type="submit" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-600">Save</button>
                                                    <button type="button" @click="editing = false" class="rounded-xl border border-orange-100 px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                </div>
                                            </form>

                                            {{-- Reply button + inline reply form --}}
                                            @auth
                                                <div class="mt-3 flex items-center gap-3">
                                                    <button type="button" @click="replying = !replying; editing = false" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400 transition hover:text-orange-600">
                                                        <svg viewBox="0 0 24 24" fill="none" class="size-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                            <path d="M9 15 3 9l6-6M3 9h13a5 5 0 0 1 0 10h-1"/>
                                                        </svg>
                                                        Reply
                                                    </button>
                                                    @if ($comment->replies->count() > 0)
                                                        <span class="text-xs text-slate-400">{{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}</span>
                                                    @endif
                                                </div>

                                                <form x-show="replying" method="POST" action="{{ route('recipes.comments.reply', [$recipe['id'], $comment->id]) }}" class="mt-3" x-cloak>
                                                    @csrf
                                                    <textarea name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100" placeholder="Write a reply..."></textarea>
                                                    <div class="mt-2 flex gap-2">
                                                        <button type="submit" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-600">Post reply</button>
                                                        <button type="button" @click="replying = false" class="rounded-xl border border-orange-100 px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                    </div>
                                                </form>
                                            @endauth

                                            {{-- Nested replies --}}
                                            @if ($comment->replies->count() > 0)
                                                <div class="mt-4 space-y-3 border-l-2 border-orange-100 pl-4">
                                                    @foreach ($comment->replies as $reply)
                                                        <article class="rounded-xl bg-white p-3" x-data="{ editing: false }">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">
                                                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                                    </span>
                                                                    <div>
                                                                        <h4 class="text-sm font-extrabold text-slate-800">{{ $reply->user->name }}</h4>
                                                                        <time class="text-xs font-medium text-slate-400" datetime="{{ $reply->created_at->toISOString() }}">{{ $reply->created_at->diffForHumans() }}</time>
                                                                    </div>
                                                                </div>
                                                                @auth
                                                                    @if (auth()->id() === $reply->user_id)
                                                                        <div class="flex items-center gap-2">
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
                                                            <div x-show="!editing" class="mt-2">
                                                                <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-600">{{ $reply->body }}</p>
                                                            </div>
                                                            <form x-show="editing" method="POST" action="{{ route('recipes.comments.update', [$recipe['id'], $reply->id]) }}" class="mt-2" x-cloak>
                                                                @csrf
                                                                @method('PATCH')
                                                                <textarea name="body" rows="2" maxlength="2000" required class="w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm leading-6 text-slate-700 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">{{ $reply->body }}</textarea>
                                                                <div class="mt-2 flex gap-2">
                                                                    <button type="submit" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-orange-600">Save</button>
                                                                    <button type="button" @click="editing = false" class="rounded-xl border border-orange-100 px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-orange-50">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </article>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </article>
                                    @empty
                                        <p class="text-sm leading-6 text-orange-600">No comments yet. Be the first to share your thoughts.</p>
                                    @endforelse
                                </div>
                            </div>
                        </section>
                    </div>

                </div>
            </section>
        </main>

        <div class="lg:hidden">
            @include('partials.kitchhug_footer')
        </div>
    </div>
@endsection
