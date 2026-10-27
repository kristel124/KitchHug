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
                            <span class="size-1.5 rounded-full bg-orange-500"></span>
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

                <section aria-labelledby="users-table-heading">
                    @if (session('status'))
                        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4">
                            <svg viewBox="0 0 24 24" fill="none" class="size-5 shrink-0 text-green-600" aria-hidden="true">
                                <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-sm font-semibold text-green-700">{{ session('status') }}</p>
                        </div>
                    @endif

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
                                <tr class="bg-orange-50/60">
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-bold uppercase tracking-widest text-slate-400">#</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Email</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Role</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Recipes</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Last active</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-bold uppercase tracking-widest text-slate-400">Joined</th>
                                    <th scope="col" class="w-12 py-3.5 pl-3 pr-6 text-center"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-50">
                                @forelse ($users as $index => $user)
                                    <tr class="transition hover:bg-orange-50/40">
                                        <td class="py-4 pl-6 pr-3 text-sm font-medium text-slate-400">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full
                                                    {{ $user->is_admin ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-600' }}
                                                    text-xs font-black">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                                <span class="text-sm font-semibold text-slate-800">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                                        <td class="px-3 py-4">
                                            @if ($user->is_admin)
                                                <span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-700 ring-1 ring-orange-200">Admin</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 ring-1 ring-slate-200">Member</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-sm font-semibold text-slate-700">
                                            {{ number_format($user->recipes_count) }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-400">
                                            @if ($user->last_seen_at)
                                                <span title="{{ $user->last_seen_at->format('M d, Y g:i A') }}">
                                                    {{ $user->last_seen_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="text-slate-300">Never</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-sm text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 pl-3 pr-6 text-center">
                                            @if (! $user->is_admin && ! $user->is(auth()->user()))
                                                <button
                                                    type="button"
                                                    data-delete-trigger
                                                    data-delete-title="Delete user account"
                                                    data-delete-message="You're about to permanently delete {{ $user->name }}. This action cannot be undone."
                                                    data-delete-action="{{ route('admin.users.destroy', $user) }}"
                                                    class="inline-flex items-center justify-center rounded-lg p-1.5 text-red-400 transition hover:bg-red-50 hover:text-red-600">
                                                    <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <span class="sr-only">Delete {{ $user->name }}</span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-16 text-center">
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
