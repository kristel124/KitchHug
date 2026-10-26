@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 md:flex">
        <aside class="flex w-full shrink-0 flex-col border-b border-orange-100 bg-white md:min-h-screen md:w-64 md:border-b-0 md:border-r">
            <div class="flex items-center justify-between px-6 py-5">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-tight text-slate-900">
                    Kitch<span class="text-orange-500">Hug</span>
                    <span class="ml-1 text-xs font-bold uppercase tracking-wider text-slate-400">Admin</span>
                </a>
            </div>

            <nav aria-label="Admin navigation" class="grid grid-cols-2 gap-2 px-4 pb-4 md:flex md:flex-1 md:flex-col md:gap-1">
                <p class="hidden px-3 pb-2 pt-5 text-xs font-bold uppercase tracking-widest text-slate-400 md:block">Workspace</p>
                <a href="{{ route('admin.dashboard') }}" aria-current="page" class="flex items-center gap-3 rounded-xl bg-orange-50 px-3 py-3 text-sm font-bold text-orange-700">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                        <path d="M3.75 10.5 12 3.75l8.25 6.75v9a.75.75 0 0 1-.75.75h-5.25v-6h-4.5v6H4.5a.75.75 0 0 1-.75-.75v-9Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
                <a href="#community-overview" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-slate-600 transition hover:bg-orange-50 hover:text-orange-700">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                        <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm5 2a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-11.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Community
                </a>
            </nav>

            <div class="hidden border-t border-slate-100 p-4 md:block">
                <div class="flex items-center gap-3 rounded-xl bg-[#FAF6F0] p-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-black text-orange-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-slate-500">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-600 transition hover:bg-orange-50 hover:text-orange-700">
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1 px-5 py-8 sm:px-8 sm:py-10 lg:px-12">
            <div class="mx-auto max-w-6xl">
                <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-orange-500">Admin dashboard</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Welcome, {{ auth()->user()->name }}</h1>
                        <p class="mt-2 text-slate-600">Here’s an overview of activity in your KitchHug community.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                        @csrf
                        <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                            Log out
                        </button>
                    </form>
                </header>

                <section id="community-overview" aria-labelledby="community-overview-heading">
                    <div class="mb-4">
                        <h2 id="community-overview-heading" class="text-lg font-bold text-slate-900">Community overview</h2>
                        <p class="mt-1 text-sm text-slate-500">A snapshot of what’s happening across KitchHug.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm shadow-orange-950/[0.03]">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-500">Registered users</p>
                                <span class="flex size-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-4xl font-black tracking-tight text-slate-900">{{ number_format($userCount) }}</p>
                            <p class="mt-1 text-sm text-slate-500">Members in your community</p>
                        </article>
                        <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm shadow-orange-950/[0.03]">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-500">Recipes shared</p>
                                <span class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-4xl font-black tracking-tight text-slate-900">{{ number_format($recipeCount) }}</p>
                            <p class="mt-1 text-sm text-slate-500">Recipes created by members</p>
                        </article>
                        <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm shadow-orange-950/[0.03] sm:col-span-2 xl:col-span-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-500">Recipe comments</p>
                                <span class="flex size-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5">
                                        <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-5 text-4xl font-black tracking-tight text-slate-900">{{ number_format($commentCount) }}</p>
                            <p class="mt-1 text-sm text-slate-500">Conversations on recipes</p>
                        </article>
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection
