@extends('layouts.app')

@section('content')
    <div class="bg-[#FAF6F0] text-slate-800 md:flex md:min-h-screen">
        @include('admin.partials.sidebar')

        <main class="min-w-0 flex-1 px-5 py-8 sm:px-8 sm:py-10 lg:px-12">
            <div class="mx-auto max-w-6xl">
                <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-orange-500">Admin dashboard</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Comments</h1>
                        <p class="mt-2 text-slate-600">All comments left by KitchHug members on recipes.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                        @csrf
                        <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                            Log out
                        </button>
                    </form>
                </header>

                <section aria-labelledby="comments-table-heading">
                    <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm shadow-orange-950/[0.03]">
                        <table class="min-w-full divide-y divide-orange-100">
                            <thead>
                                <tr class="bg-orange-50/60">
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-widest text-slate-500">#</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Author</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Comment</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Recipe</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Posted</th>
                                    <th scope="col" class="w-12 py-3.5 pl-3 pr-6"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-50">
                                @forelse ($comments as $comment)
                                    <tr class="transition hover:bg-orange-50/40">
                                        <td class="py-4 pl-6 pr-3 text-sm text-slate-400">{{ $comment->id }}</td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-rose-100 text-xs font-black text-rose-700">
                                                    {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                                                </span>
                                                <span class="text-sm text-slate-600">{{ $comment->user?->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-700">
                                            <p class="line-clamp-2 max-w-sm">{{ $comment->body }}</p>
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            @if ($comment->recipe)
                                                <a href="{{ route('recipes.show', $comment->recipe) }}" class="font-semibold text-slate-800 hover:text-orange-600 transition">
                                                    {{ $comment->recipe->title }}
                                                </a>
                                            @elseif ($comment->featured_recipe_slug)
                                                <span class="text-slate-500">{{ $comment->featured_recipe_slug }}</span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-500">{{ $comment->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 pl-3 pr-6 text-center">
                                            <button
                                                type="button"
                                                data-delete-trigger
                                                data-delete-title="Delete comment"
                                                data-delete-message="Delete this comment by {{ addslashes($comment->user?->name ?? 'Unknown') }}? This cannot be undone."
                                                data-delete-action="{{ route('admin.comments.destroy', $comment) }}"
                                                class="inline-flex items-center justify-center rounded-lg p-1.5 text-slate-300 transition hover:bg-red-50 hover:text-red-500"
                                                title="Delete comment">
                                                <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                                                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-sm text-slate-400">No comments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($comments->hasPages())
                        <div class="mt-6">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
    @include('admin.partials.delete-modal')
@endsection
