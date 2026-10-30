@extends('layouts.app')

@section('content')
    <div class="bg-[#FAF6F0] text-slate-800 md:flex md:min-h-screen">
        @include('admin.partials.sidebar')

        <main class="min-w-0 flex-1 px-5 py-8 sm:px-8 sm:py-10 lg:px-12">
            <div class="mx-auto max-w-4xl">

                {{-- Back + Delete --}}
                <div class="mb-6 flex items-center justify-between">
                    <a href="{{ route('admin.recipes.index') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-orange-600">
                        <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                            <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Recipes
                    </a>
                    <button
                        type="button"
                        data-delete-trigger
                        data-delete-title="Delete recipe"
                        data-delete-message="Delete &quot;{{ addslashes($recipe->title) }}&quot;? The owner will be notified. This cannot be undone."
                        data-delete-action="{{ route('admin.recipes.destroy', $recipe) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-4 py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-100 hover:text-red-700">
                        <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                            <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Delete Recipe
                    </button>
                </div>

                {{-- Hero card: title left, image right --}}
                <div class="mb-6 overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm shadow-orange-950/[0.03]">
                    <div class="flex flex-col sm:flex-row sm:items-stretch overflow-hidden">
                        {{-- Left: image --}}
                        @if ($recipe->image_path)
                            <div class="shrink-0 border-b border-orange-100 p-4 sm:w-64 sm:border-b-0 sm:border-r">
                                <img
                                    src="{{ Storage::url($recipe->image_path) }}"
                                    alt="{{ $recipe->title }}"
                                    class="h-48 w-full rounded-xl object-cover object-center"
                                />
                            </div>
                        @endif

                        {{-- Right: title, author, meta --}}
                        <div class="flex min-w-0 flex-1 flex-col justify-between gap-4 p-5 sm:p-7">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-orange-500">Recipe detail</p>
                                <h1 class="mt-2 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $recipe->title }}</h1>
                                <div class="mt-3 flex items-center gap-2.5">
                                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-200 to-amber-100 text-xs font-black text-orange-700">
                                        {{ strtoupper(substr($recipe->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-orange-600">{{ $recipe->user?->name ?? '—' }}</span>
                                </div>
                                @if ($recipe->description)
                                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $recipe->description }}</p>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200/60">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true">
                                        <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $recipe->category)) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200/60">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-3.5 text-slate-400" aria-hidden="true">
                                        <path d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ $recipe->prep_time_minutes }} min
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600 ring-1 ring-rose-200/60">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true">
                                        <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ $recipe->comments->count() }} {{ Str::plural('comment', $recipe->comments->count()) }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs text-slate-500 ring-1 ring-slate-200/60">
                                    {{ $recipe->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>


                    </div>
                </div>

                {{-- Content grid --}}
                <div class="grid gap-6 lg:grid-cols-5">

                    {{-- Left: ingredients --}}
                    <div class="space-y-6 lg:col-span-2">
                        <div class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm shadow-orange-950/[0.03]">
                            <p class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                                <span class="inline-block size-1.5 rounded-full bg-amber-400"></span>
                                Ingredients
                            </p>
                            <ul class="space-y-2">
                                @foreach (array_filter(preg_split('/\r?\n/', $recipe->ingredients)) as $ingredient)
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-orange-300"></span>
                                        {{ $ingredient }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Right: instructions + comments --}}
                    <div class="space-y-6 lg:col-span-3">

                        {{-- Instructions --}}
                        <div class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm shadow-orange-950/[0.03]">
                            <p class="mb-4 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                                <span class="inline-block size-1.5 rounded-full bg-slate-400"></span>
                                Instructions
                            </p>
                            <ol class="space-y-4">
                                @foreach (array_filter(preg_split('/\r?\n/', $recipe->instructions)) as $index => $step)
                                    <li class="flex items-start gap-3">
                                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-black text-orange-700">
                                            {{ $index + 1 }}
                                        </span>
                                        <p class="pt-0.5 text-sm leading-relaxed text-slate-700">{{ $step }}</p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        {{-- Comments --}}
                        <div class="rounded-2xl border border-orange-100 bg-white shadow-sm shadow-orange-950/[0.03]">
                            <div class="flex items-center justify-between border-b border-orange-50 px-6 py-4">
                                <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                                    <span class="inline-block size-1.5 rounded-full bg-rose-400"></span>
                                    Comments
                                </p>
                                <span class="rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-600">
                                    {{ $recipe->comments->count() }}
                                </span>
                            </div>
                            @forelse ($recipe->comments as $comment)
                                <div class="group flex items-start gap-4 border-b border-orange-50 px-6 py-4 last:border-b-0">
                                    <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-full bg-rose-100 text-xs font-black text-rose-700">
                                        {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                            <span class="text-sm font-bold text-slate-800">{{ $comment->user?->name ?? 'Unknown' }}</span>
                                            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            <span class="text-slate-200">·</span>
                                            <span class="text-xs text-slate-400">{{ $comment->created_at->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $comment->body }}</p>
                                    </div>
                                    <button
                                        type="button"
                                        data-delete-trigger
                                        data-delete-title="Delete comment"
                                        data-delete-message="Delete this comment by {{ addslashes($comment->user?->name ?? 'Unknown') }}? This cannot be undone."
                                        data-delete-action="{{ route('admin.comments.destroy', $comment) }}"
                                        class="shrink-0 rounded-lg p-1.5 text-slate-300 transition hover:bg-red-50 hover:text-red-500"
                                        title="Delete comment">
                                        <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                                            <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="flex flex-col items-center gap-2 py-10 text-center">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-8 text-slate-200" aria-hidden="true">
                                        <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="text-sm text-slate-400">No comments yet.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
    @include('admin.partials.delete-modal')
@endsection
