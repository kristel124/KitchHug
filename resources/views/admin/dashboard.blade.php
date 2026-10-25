@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800">
        <header class="border-b border-orange-100 bg-white shadow-sm">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-black text-slate-900">
                    Kitch<span class="text-orange-500">Hug</span>
                    <span class="ml-2 text-sm font-semibold text-slate-500">Admin</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-orange-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                        Log out
                    </button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-widest text-orange-500">Admin dashboard</p>
                <h1 class="mt-2 text-3xl font-black text-slate-900">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-slate-600">Here’s an overview of activity in your KitchHug community.</p>
            </div>

            <section aria-label="Community overview" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-500">Registered users</p>
                    <p class="mt-3 text-4xl font-black text-slate-900">{{ $userCount }}</p>
                </article>
                <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-500">Recipes shared</p>
                    <p class="mt-3 text-4xl font-black text-slate-900">{{ $recipeCount }}</p>
                </article>
                <article class="rounded-2xl border border-orange-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-slate-500">Recipe comments</p>
                    <p class="mt-3 text-4xl font-black text-slate-900">{{ $commentCount }}</p>
                </article>
            </section>
        </main>
    </div>
@endsection
