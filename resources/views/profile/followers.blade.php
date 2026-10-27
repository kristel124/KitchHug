@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen flex-col bg-[#FAF6F0] text-slate-800 antialiased">
        @include('partials.kitchhug_header')

        <main class="flex-1">
            {{-- Header --}}
            <section class="border-b border-orange-100 bg-gradient-to-b from-orange-100/80 to-[#FAF6F0]">
                <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                    <a href="{{ route('users.show', $user) }}" class="inline-flex items-center gap-1 text-sm font-bold text-orange-600 transition hover:text-orange-700">
                        <svg viewBox="0 0 24 24" fill="none" class="size-4" aria-hidden="true">
                            <path d="M15.75 19.5 8.25 12l7.5-7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to {{ $user->name }}'s profile
                    </a>

                    <div class="mt-5 flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-900 text-lg font-black text-white shadow">
                            @if ($user->profile_photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}"
                                     alt="{{ $user->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                {{ $user->initials() }}
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-orange-500">{{ $user->name }}</p>
                            <h1 class="mt-0.5 text-xl font-black tracking-tight text-slate-900">
                                {{ $tab === 'followers' ? 'Followers' : 'Following' }}
                            </h1>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div class="mt-6 flex gap-1 rounded-2xl border border-orange-100 bg-white/80 p-1 shadow-sm sm:w-fit">
                        <a href="{{ route('users.followers', $user) }}"
                           class="flex-1 rounded-xl px-5 py-2 text-center text-sm font-bold transition sm:flex-none
                                  {{ $tab === 'followers' ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-500 hover:text-orange-600' }}">
                            Followers
                            <span class="ml-1.5 rounded-full {{ $tab === 'followers' ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }} px-1.5 py-0.5 text-xs font-bold">
                                {{ $user->followers()->count() }}
                            </span>
                        </a>
                        <a href="{{ route('users.following', $user) }}"
                           class="flex-1 rounded-xl px-5 py-2 text-center text-sm font-bold transition sm:flex-none
                                  {{ $tab === 'following' ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-500 hover:text-orange-600' }}">
                            Following
                            <span class="ml-1.5 rounded-full {{ $tab === 'following' ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }} px-1.5 py-0.5 text-xs font-bold">
                                {{ $user->following()->count() }}
                            </span>
                        </a>
                    </div>
                </div>
            </section>

            {{-- People list --}}
            <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
                @if ($people->isEmpty())
                    <div class="flex flex-col items-center gap-4 rounded-3xl border border-dashed border-orange-200 bg-white px-6 py-16 text-center">
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-orange-50 text-orange-300">
                            <svg viewBox="0 0 24 24" fill="none" class="size-7" aria-hidden="true">
                                <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            @if ($tab === 'followers')
                                <p class="text-base font-bold text-slate-700">No followers yet</p>
                                <p class="mt-1 text-sm text-slate-400">When people follow {{ $isOwnProfile ? 'you' : $user->name }}, they'll appear here.</p>
                            @else
                                <p class="text-base font-bold text-slate-700">Not following anyone yet</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $isOwnProfile ? 'You aren\'t' : $user->name . ' isn\'t' }} following anyone yet.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">
                        @foreach ($people as $person)
                            @php
                                $viewerFollows = $viewer instanceof \App\Models\User
                                    && $viewer->id !== $person->id
                                    && $viewer->following()->whereKey($person->id)->exists();
                            @endphp
                            <div class="flex items-center gap-4 border-b border-orange-50 px-5 py-4 last:border-b-0">
                                {{-- Avatar --}}
                                <a href="{{ route('users.show', $person) }}" class="shrink-0">
                                    <div class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-slate-900 text-sm font-black text-white ring-2 ring-white">
                                        @if ($person->profile_photo_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($person->profile_photo_path) }}"
                                                 alt="{{ $person->name }}"
                                                 class="size-11 object-cover">
                                        @else
                                            {{ $person->initials() }}
                                        @endif
                                    </div>
                                </a>

                                {{-- Name --}}
                                <a href="{{ route('users.show', $person) }}" class="min-w-0 flex-1 text-sm font-bold text-slate-900 transition hover:text-orange-600 truncate">
                                    {{ $person->name }}
                                </a>

                                {{-- Follow button --}}
                                @if ($viewer instanceof \App\Models\User && $viewer->id !== $person->id)
                                    <form method="POST" action="{{ route('users.follow', $person) }}" class="shrink-0">
                                        @csrf
                                        <button type="submit"
                                                class="rounded-xl px-4 py-1.5 text-xs font-bold transition
                                                       {{ $viewerFollows
                                                           ? 'border border-orange-200 bg-white text-slate-600 hover:bg-orange-50'
                                                           : 'bg-orange-500 text-white hover:bg-orange-600' }}">
                                            {{ $viewerFollows ? 'Following' : '＋ Follow' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>

        @include('partials.kitchhug_footer')
    </div>
@endsection
