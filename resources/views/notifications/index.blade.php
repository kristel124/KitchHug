@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen flex-col bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main class="mx-auto w-full max-w-3xl flex-1 px-4 py-10 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-orange-600">
                        <span class="size-1.5 rounded-full bg-orange-500"></span>
                        Your activity
                    </span>
                    <h1 class="mt-3 text-2xl font-black tracking-tight text-slate-900">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        @if ($unreadCount > 0)
                            <span class="font-semibold text-orange-600">{{ $unreadCount }} unread</span>
                        @else
                            All caught up
                        @endif
                    </p>
                </div>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-xl border border-orange-200 bg-white px-4 py-2 text-sm font-semibold text-orange-700 shadow-sm transition hover:bg-orange-50">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4">
                    <svg viewBox="0 0 24 24" fill="none" class="size-5 shrink-0 text-green-600" aria-hidden="true">
                        <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm font-semibold text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <div class="space-y-3">
                @forelse ($notifications as $notification)
                    <article class="group flex items-start gap-4 rounded-2xl border bg-white p-5 shadow-sm transition
                        {{ $notification->read_at ? 'border-slate-100' : 'border-orange-200 bg-orange-50/40' }}">

                        {{-- Activity icon --}}
                        @php $activity = $notification->data['activity'] ?? ''; @endphp
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full
                            @if ($activity === 'recipe_comment') bg-blue-100 text-blue-600
                            @elseif ($activity === 'new_recipe') bg-amber-100 text-amber-600
                            @elseif ($activity === 'new_follower') bg-orange-100 text-orange-600
                            @elseif ($activity === 'recipe_deleted' || $activity === 'comment_deleted') bg-red-100 text-red-600
                            @else bg-slate-100 text-slate-500
                            @endif">
                            @if ($activity === 'recipe_comment')
                                {{-- Comment --}}
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                                    <path d="M20.25 11.5a7.75 7.75 0 0 1-8 7.5 8.6 8.6 0 0 1-3.5-.75l-4.5 1 1.5-3.5a7.2 7.2 0 0 1-1.5-4.25 7.75 7.75 0 0 1 8-7.5 7.75 7.75 0 0 1 8 7.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @elseif ($activity === 'new_recipe')
                                {{-- Recipe/share --}}
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                                    <path d="M7.75 3.75h8.5v4.5a4.25 4.25 0 0 1-8.5 0v-4.5Zm0 2H4.5v1.5a4.25 4.25 0 0 0 4.25 4.25m7.5-5.75h3.25v1.5a4.25 4.25 0 0 1-4.25 4.25m-3.25 1v3.75m-3.5 4h7m-3.5-4a3.5 3.5 0 0 0-3.5 3.5v.5h7v-.5a3.5 3.5 0 0 0-3.5-3.5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @elseif ($activity === 'new_follower')
                                {{-- Follow --}}
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                                    <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @elseif ($activity === 'recipe_deleted' || $activity === 'comment_deleted')
                                {{-- Violation/removed --}}
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                                    <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                {{-- Generic bell --}}
                                <svg viewBox="0 0 24 24" fill="none" class="size-5" aria-hidden="true">
                                    <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>

                        {{-- Content --}}
                        <div class="min-w-0 flex-1">
                            <p class="text-sm leading-relaxed text-slate-700" spellcheck="false">
                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                            </p>
                            <time class="mt-1 block text-xs text-slate-400" datetime="{{ $notification->created_at->toISOString() }}">
                                {{ $notification->created_at->diffForHumans() }}
                            </time>
                            <form method="POST" action="{{ route('notifications.open', $notification->id) }}" class="mt-3">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 transition hover:text-orange-700">
                                    Open
                                    <svg viewBox="0 0 16 16" fill="currentColor" class="size-3">
                                        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06l-3.25 3.25a.75.75 0 0 1-1.06-1.06L8.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- Unread dot --}}
                        @unless ($notification->read_at)
                            <span class="mt-1.5 size-2 shrink-0 rounded-full bg-orange-500" aria-label="Unread"></span>
                        @endunless
                    </article>
                @empty
                    <div class="flex flex-col items-center gap-4 rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-16 text-center">
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-orange-50 text-orange-300">
                            <svg viewBox="0 0 24 24" fill="none" class="size-7" aria-hidden="true">
                                <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">You're all caught up</h2>
                            <p class="mt-1 text-sm text-slate-500">New posts, comments, and followers will appear here.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
