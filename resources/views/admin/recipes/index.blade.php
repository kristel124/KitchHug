@extends('layouts.app')

@section('content')
    <div class="bg-[#FAF6F0] text-slate-800 md:flex md:min-h-screen">
        @include('admin.partials.sidebar')

        <main class="min-w-0 flex-1 px-5 py-8 sm:px-8 sm:py-10 lg:px-12">
            <div class="mx-auto max-w-6xl">

                <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-orange-500">Admin dashboard</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Recipes</h1>
                        <p class="mt-2 text-slate-600">All recipes shared by KitchHug members.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-xl border border-orange-100 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm">
                            {{ $recipes->total() }} {{ Str::plural('recipe', $recipes->total()) }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                            @csrf
                            <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                                Log out
                            </button>
                        </form>
                    </div>
                </header>

                @if (session('status'))
                    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4">
                        <svg viewBox="0 0 24 24" fill="none" class="size-5 shrink-0 text-green-600" aria-hidden="true">
                            <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm font-semibold text-green-700">{{ session('status') }}</p>
                    </div>
                @endif

                <section aria-labelledby="recipes-table-heading">
                    <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm shadow-orange-950/[0.03]">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-orange-100 bg-gradient-to-r from-orange-50/80 to-amber-50/40">
                                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-widest text-slate-400">#</th>
                                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Title</th>
                                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Author</th>
                                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Category</th>
                                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Comments</th>
                                    <th scope="col" class="px-3 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Posted</th>
                                    <th scope="col" class="px-3 py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recipes as $index => $recipe)
                                    <tr class="group border-b border-orange-50/80 transition last:border-b-0 hover:bg-orange-50/50">
                                        <td class="py-4 pl-6 pr-3 text-sm font-semibold text-slate-300">
                                            {{ $recipes->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center gap-3">
                                                {{-- Thumbnail --}}
                                                <div class="size-10 shrink-0 overflow-hidden rounded-lg bg-orange-50">
                                                    @if ($recipe->image_path)
                                                        <img src="{{ Storage::url($recipe->image_path) }}"
                                                             alt="{{ $recipe->title }}"
                                                             class="size-10 object-cover" />
                                                    @else
                                                        <div class="flex size-10 items-center justify-center">
                                                            <svg viewBox="0 0 24 24" fill="none" class="size-5 text-orange-200" aria-hidden="true">
                                                                <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.recipes.show', $recipe) }}"
                                                   class="text-sm font-bold text-slate-800 transition hover:text-orange-600">
                                                    {{ $recipe->title }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-200 to-amber-100 text-xs font-black text-orange-700 shadow-sm">
                                                    {{ strtoupper(substr($recipe->user?->name ?? '?', 0, 1)) }}
                                                </span>
                                                <span class="text-sm font-medium text-slate-700">{{ $recipe->user?->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4">
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200/60">
                                                {{ ucfirst(str_replace('-', ' ', $recipe->category)) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4">
                                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-slate-600">
                                                <svg viewBox="0 0 24 24" fill="none" class="size-3.5 text-slate-400" aria-hidden="true">
                                                    <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                {{ number_format($recipe->comments_count) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-400">{{ $recipe->created_at->format('M d, Y') }}</td>
                                        <td class="px-3 py-4">
                                            <button
                                                type="button"
                                                data-delete-trigger
                                                data-delete-title="Delete recipe"
                                                data-delete-message="Delete &quot;{{ addslashes($recipe->title) }}&quot;? The owner will be notified. This cannot be undone."
                                                data-delete-action="{{ route('admin.recipes.destroy', $recipe) }}"
                                                class="rounded-lg p-2 text-slate-300 transition hover:bg-red-50 hover:text-red-500"
                                                title="Delete recipe">
                                                <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                                                    <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-16 text-center">
                                            <svg viewBox="0 0 24 24" fill="none" class="mx-auto mb-3 size-10 text-slate-200" aria-hidden="true">
                                                <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-400">No recipes found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($recipes->hasPages())
                        <div class="mt-6">
                            {{ $recipes->links() }}
                        </div>
                    @endif
                </section>

            </div>
        </main>
    </div>
    @include('admin.partials.delete-modal')
@endsection
