@extends('layouts.app')

@section('content')
    <div class="bg-[#FAF6F0] text-slate-800 md:flex md:min-h-screen">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        <main class="min-w-0 flex-1 px-5 py-8 sm:px-8 sm:py-10 lg:px-12">
            <div class="mx-auto max-w-6xl">

                {{-- Page Header --}}
                <header class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-orange-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-orange-600">
                            <span class="size-1.5 animate-pulse rounded-full bg-orange-500"></span>
                            Admin dashboard
                        </span>
                        <h1 class="mt-3 text-2xl font-black tracking-tight text-slate-900">Welcome back</h1>
                        <p class="mt-1 text-slate-500">Here's what's happening across KitchHug today.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                        @csrf
                        <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                            Log out
                        </button>
                    </form>
                </header>

                {{-- Stats Cards --}}
                <section id="community-overview" aria-labelledby="community-overview-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <h2 id="community-overview-heading" class="text-sm font-bold uppercase tracking-widest text-slate-400">Community overview</h2>
                        <div class="h-px flex-1 bg-orange-100"></div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        {{-- Users Card --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="group relative overflow-hidden rounded-2xl border border-orange-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-orange-300 hover:shadow-xl hover:shadow-orange-100">
                            <div class="absolute inset-x-0 top-0 h-1 rounded-t-2xl bg-gradient-to-r from-orange-400 to-orange-500"></div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Registered users</p>
                                    <p class="mt-4 text-5xl font-black tracking-tight text-slate-900">{{ number_format($userCount) }}</p>
                                    <p class="mt-2 text-sm text-slate-400">Members in your community</p>
                                </div>
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-500 ring-1 ring-orange-100 transition group-hover:bg-orange-100">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-5 flex items-center gap-1 text-xs font-semibold text-orange-500">
                                View all users
                                <svg viewBox="0 0 16 16" fill="currentColor" class="size-3 transition group-hover:translate-x-0.5">
                                    <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </a>

                        {{-- Recipes Card --}}
                        <a href="{{ route('admin.recipes.index') }}"
                           class="group relative overflow-hidden rounded-2xl border border-amber-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-100">
                            <div class="absolute inset-x-0 top-0 h-1 rounded-t-2xl bg-gradient-to-r from-amber-400 to-amber-500"></div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Recipes shared</p>
                                    <p class="mt-4 text-5xl font-black tracking-tight text-slate-900">{{ number_format($recipeCount) }}</p>
                                    <p class="mt-2 text-sm text-slate-400">Recipes created by members</p>
                                </div>
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-500 ring-1 ring-amber-100 transition group-hover:bg-amber-100">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-5 flex items-center gap-1 text-xs font-semibold text-amber-500">
                                View all recipes
                                <svg viewBox="0 0 16 16" fill="currentColor" class="size-3 transition group-hover:translate-x-0.5">
                                    <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </a>

                        {{-- Active Posters Card --}}
                        <div class="group relative overflow-hidden rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-100">
                            <div class="absolute inset-x-0 top-0 h-1 rounded-t-2xl bg-gradient-to-r from-emerald-400 to-teal-400"></div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Active posters</p>
                                    <p class="mt-4 text-5xl font-black tracking-tight text-slate-900">{{ $activePostersRate }}<span class="text-2xl text-slate-400">%</span></p>
                                    <p class="mt-2 text-sm text-slate-400">Of members have shared a recipe</p>
                                </div>
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-500 ring-1 ring-orange-100 transition group-hover:bg-orange-100">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M3 13.5l5.25-5.25L12 12l3.75-3.75L21 13.5M3 13.5v4.5h18v-4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-emerald-50">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-400 transition-all" style="width: {{ $activePostersRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- User Activity Log --}}
                <section id="activity-log" aria-labelledby="activity-log-heading" class="mt-12">
                    <div class="mb-5 flex items-center gap-3">
                        <h2 id="activity-log-heading" class="text-sm font-bold uppercase tracking-widest text-slate-400">User activity log</h2>
                        <div class="h-px flex-1 bg-orange-100"></div>
                        @if (count($activityLog))
                            <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-600">
                                {{ count($activityLog) }}
                            </span>
                        @endif
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">
                        @forelse ($activityLog as $entry)
                            <div class="flex items-start gap-4 border-b border-slate-50 px-6 py-4 transition hover:bg-orange-50/40 last:border-b-0">
                                {{-- Avatar --}}
                                <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-full
                                    {{ $entry['type'] === 'recipe' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700' }}
                                    text-xs font-black">
                                    {{ strtoupper(substr($entry['user']?->name ?? '?', 0, 1)) }}
                                </span>

                                {{-- Content --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-slate-700">
                                        <span class="font-bold text-slate-900">{{ $entry['user']?->name ?? 'Unknown user' }}</span>
                                        {{ $entry['label'] }}
                                        @if ($entry['detail'])
                                            — <span class="font-semibold text-slate-700">{{ $entry['detail'] }}</span>
                                        @endif
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400">
                                        {{ $entry['happened_at']->diffForHumans() }}
                                        <span class="mx-1 text-slate-300">·</span>
                                        {{ $entry['happened_at']->format('M d, Y g:i A') }}
                                    </p>
                                </div>

                                {{-- Type badge --}}
                                <span class="shrink-0 self-center">
                                    @if ($entry['type'] === 'recipe')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-100">
                                            <svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true">
                                                <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Recipe
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-100">
                                            <svg viewBox="0 0 24 24" fill="none" class="size-3.5" aria-hidden="true">
                                                <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Comment
                                        </span>
                                    @endif
                                </span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center gap-3 py-20 text-center">
                                <span class="flex size-14 items-center justify-center rounded-2xl bg-orange-50 text-orange-300">
                                    <svg viewBox="0 0 24 24" fill="none" class="size-7" aria-hidden="true">
                                        <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-500">No activity yet</p>
                                    <p class="mt-1 text-xs text-slate-400">Member actions will appear here once they start using KitchHug.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>
        </main>
    </div>
@endsection
