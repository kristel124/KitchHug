@extends('layouts.app')

@section('content')
    <div class="bg-[#FAF6F0] text-slate-800 md:flex md:min-h-screen">
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
                        <h1 class="mt-3 text-2xl font-black tracking-tight text-slate-900">Users</h1>
                        <p class="mt-1 text-slate-500">All registered members of KitchHug.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                        @csrf
                        <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-orange-50">
                            Log out
                        </button>
                    </form>
                </header>

                {{-- Stats Strip --}}
                <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-2xl border border-orange-100 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total</p>
                        <p class="mt-1 text-3xl font-black text-slate-900">{{ $users->total() }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">Registered members</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Admins</p>
                        <p class="mt-1 text-3xl font-black text-orange-500">{{ $users->getCollection()->where('is_admin', true)->count() }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">With admin access</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Active</p>
                        <p class="mt-1 text-3xl font-black text-slate-900">{{ $users->getCollection()->whereNotNull('last_seen_at')->count() }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">Seen at least once</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-white px-5 py-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">New today</p>
                        <p class="mt-1 text-3xl font-black text-slate-900">{{ $users->getCollection()->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">Joined today</p>
                    </div>
                </div>

                <section aria-labelledby="users-table-heading">
                    <div class="mb-5 flex items-center gap-3">
                        <h2 id="users-table-heading" class="text-sm font-bold uppercase tracking-widest text-slate-400">All members</h2>
                        <div class="h-px flex-1 bg-orange-100"></div>
                        <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-600">
                            {{ $users->total() }}
                        </span>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">
                        <table class="min-w-full divide-y divide-orange-100">
                            <thead>
                                <tr class="bg-gradient-to-r from-orange-50/80 to-amber-50/40">
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-widest text-slate-400">#</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Email</th>

                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Recipes</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Last active</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Joined</th>
                                    <th scope="col" class="w-12 py-3.5 pl-3 pr-6 text-center"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-50">
                                @forelse ($users as $index => $user)
                                    <tr class="group transition hover:bg-orange-50/50">
                                        <td class="py-4 pl-6 pr-3 text-sm font-semibold text-slate-300">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br
                                                    {{ $user->is_admin ? 'from-orange-200 to-amber-100 text-orange-700' : 'from-slate-100 to-slate-200 text-slate-600' }}
                                                    text-xs font-black shadow-sm">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-500">{{ $user->email }}</td>

                                        <td class="px-3 py-4">
                                            <span class="text-sm font-bold text-slate-700">
                                                {{ number_format($user->recipes_count) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            @if ($user->last_seen_at)
                                                <span class="inline-flex items-center gap-1.5 text-slate-500" title="{{ $user->last_seen_at->format('M d, Y g:i A') }}">
                                                    <span class="size-1.5 rounded-full bg-orange-400"></span>
                                                    {{ $user->last_seen_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-slate-300">
                                                    <span class="size-1.5 rounded-full bg-slate-200"></span>
                                                    Never
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-400">
                                            <span title="{{ $user->created_at->format('M d, Y g:i A') }}">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td class="py-4 pl-3 pr-6 text-center">
                                            @if (! $user->is_admin && ! $user->is(auth()->user()))
                                                <button
                                                    type="button"
                                                    data-delete-trigger
                                                    data-delete-title="Delete user account"
                                                    data-delete-message="You're about to permanently delete {{ $user->name }}. This action cannot be undone."
                                                    data-delete-action="{{ route('admin.users.destroy', $user) }}"
                                                    class="rounded-lg p-2 text-slate-300 transition hover:bg-red-50 hover:text-red-500"
                                                    title="Delete user">
                                                    <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                                                        <path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <span class="sr-only">Delete {{ $user->name }}</span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-16 text-center">
                                            <svg viewBox="0 0 24 24" fill="none" class="mx-auto mb-3 size-10 text-slate-200" aria-hidden="true">
                                                <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-400">No users found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>

    @include('admin.partials.delete-modal')
@endsection
