@foreach ($people as $person)
    @php
        $viewerFollows = $viewer instanceof \App\Models\User
            && $viewer->id !== $person->id
            && $viewer->following()->whereKey($person->id)->exists();
    @endphp
    <div data-person-row data-person-name="{{ strtolower($person->name) }}"
         class="flex items-center gap-4 border-b border-slate-100 px-5 py-4 last:border-b-0">
        <a href="{{ route('users.show', $person) }}" class="shrink-0">
            <div class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-slate-800 text-sm font-black text-white ring-2 ring-white">
                @if ($person->profile_photo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($person->profile_photo_path) }}"
                         alt="{{ $person->name }}"
                         class="size-11 object-cover">
                @else
                    {{ $person->initials() }}
                @endif
            </div>
        </a>
        <a href="{{ route('users.show', $person) }}" class="min-w-0 flex-1 truncate text-sm font-bold text-slate-900 transition hover:text-orange-600">
            {{ $person->name }}
        </a>
        @if ($viewer instanceof \App\Models\User && $viewer->id !== $person->id)
            <form method="POST" action="{{ route('users.follow', $person) }}" class="shrink-0">
                @csrf
                <button type="submit"
                        class="rounded-xl px-4 py-1.5 text-xs font-bold transition
                               {{ $viewerFollows
                                   ? 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'
                                   : 'bg-orange-500 text-white hover:bg-orange-600' }}">
                    {{ $viewerFollows ? 'Following' : '＋ Follow' }}
                </button>
            </form>
        @endif
    </div>
@endforeach

@if ($people->isEmpty())
    <div class="flex flex-col items-center gap-3 px-5 py-14 text-center">
        <span class="flex size-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
            <svg viewBox="0 0 24 24" fill="none" class="size-6" aria-hidden="true">
                <path d="M16 19.25h4.25v-1.5a3.25 3.25 0 0 0-5.8-2.03M16 19.25H8m8 0v-1.5c0-.72-.15-1.41-.42-2.03M8 19.25H3.75v-1.5a3.25 3.25 0 0 1 5.8-2.03M8 19.25v-1.5c0-.72.15-1.41.42-2.03m0 0a4 4 0 0 1 7.16 0M15 7.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <p class="text-sm font-semibold text-slate-500">Nobody here yet</p>
    </div>
@endif
