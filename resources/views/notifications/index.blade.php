@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4 border-b border-orange-100 pb-5">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-widest text-orange-600">Your activity</p>
                    <h1 class="mt-2 text-3xl font-black text-slate-900">Notifications</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $unreadCount }} unread</p>
                </div>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-orange-200 bg-white px-4 py-2.5 text-sm font-semibold text-orange-700 transition hover:bg-orange-50">Mark all as read</button>
                    </form>
                @endif
            </div>

            @if (session('status'))
                <p role="status" class="mt-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
            @endif

            <div class="mt-6 space-y-3">
                @forelse ($notifications as $notification)
                    <article class="flex items-start gap-4 rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-orange-200 bg-orange-50/60' }} p-4 shadow-sm sm:p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-orange-100 text-sm font-bold text-orange-800">
                            @if ($notification->data['actor']['profile_photo_path'] ?? null)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($notification->data['actor']['profile_photo_path']) }}" alt="{{ $notification->data['actor']['name'] }} profile photo" class="h-full w-full object-cover">
                            @else
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($notification->data['actor']['name'] ?? 'K', 0, 1)) }}
                            @endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm leading-6 text-slate-700">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>
                            <time class="mt-1 block text-xs text-slate-500" datetime="{{ $notification->created_at->toISOString() }}">{{ $notification->created_at->diffForHumans() }}</time>
                            <form method="POST" action="{{ route('notifications.open', $notification->id) }}" class="mt-3">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-orange-700 hover:text-orange-800">Open <span aria-hidden="true">→</span></button>
                            </form>
                        </div>
                        @unless ($notification->read_at)
                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-orange-500" aria-label="Unread"></span>
                        @endunless
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-14 text-center">
                        <h2 class="text-lg font-bold text-slate-900">You’re all caught up</h2>
                        <p class="mt-2 text-sm text-slate-500">New posts from people you follow, comments on your recipes, and new followers will appear here.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
