@forelse ($users as $person)
    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3 last:border-b-0">
        @if ($person['url'])
            <a href="{{ $person['url'] }}" class="shrink-0">
        @else
            <span class="shrink-0">
        @endif
            <span class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-rose-100 text-sm font-black text-rose-700">
                @if ($person['profile_photo_path'])
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($person['profile_photo_path']) }}"
                         alt="{{ $person['name'] }}"
                         class="size-10 object-cover">
                @else
                    {{ $person['initials'] }}
                @endif
            </span>
        @if ($person['url'])
            </a>
        @else
            </span>
        @endif
        <div class="min-w-0 flex-1">
            @if ($person['url'])
                <a href="{{ $person['url'] }}" class="text-sm font-bold text-slate-900 hover:text-rose-600 transition">{{ $person['name'] }}</a>
            @else
                <span class="text-sm font-bold text-slate-900">{{ $person['name'] }}</span>
            @endif
            <p class="text-xs text-slate-400">{{ $person['reacted_at'] }}</p>
        </div>
        <span class="text-rose-500">♥</span>
    </div>
@empty
    <div class="flex flex-col items-center gap-3 px-5 py-12 text-center">
        <span class="text-3xl">♡</span>
        <p class="text-sm font-semibold text-slate-400">No reactions yet</p>
    </div>
@endforelse
