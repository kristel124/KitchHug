<div class="flex flex-wrap items-center gap-3 border-t border-slate-100 px-5 py-3">
    @auth
        @if (auth()->id() === $recipe->user_id)
            {{-- Owner: clickable count opens modal --}}
            <button type="button"
                    onclick="openReactionsModal({{ $recipe->id }})"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                <span aria-hidden="true">♥</span>
                <span>{{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }}</span>
            </button>
        @else
            <div class="flex items-center gap-1">
                <form method="POST" action="{{ route('recipes.reactions.toggle', $recipe) }}">
                    @csrf
                    <button type="submit"
                            aria-label="{{ $recipe->is_liked ? 'Remove heart reaction' : 'React with a heart' }}"
                            aria-pressed="{{ $recipe->is_liked ? 'true' : 'false' }}"
                            class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition
                                   {{ $recipe->is_liked ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-slate-50 text-slate-700 hover:bg-rose-50 hover:text-rose-700' }}">
                        <span aria-hidden="true">{{ $recipe->is_liked ? '♥' : '♡' }}</span>
                    </button>
                </form>
                <button type="button"
                        onclick="openReactionsModal({{ $recipe->id }})"
                        class="rounded-lg px-2 py-2 text-sm font-semibold text-slate-600 transition hover:bg-rose-50 hover:text-rose-700">
                    {{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }}
                </button>
            </div>
        @endif
    @else
        <div class="flex items-center gap-1">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-rose-50 hover:text-rose-700">
                <span aria-hidden="true">♡</span>
            </a>
            <button type="button"
                    onclick="openReactionsModal({{ $recipe->id }})"
                    class="rounded-lg px-2 py-2 text-sm font-semibold text-slate-600 transition hover:bg-rose-50 hover:text-rose-700">
                {{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }}
            </button>
        </div>
    @endauth

    <a href="{{ auth()->check() ? route('recipes.show', $recipe).'#comments' : route('login') }}"
       class="ml-auto inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 transition hover:text-orange-700">
        <svg viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.25 6.75h7.5M8.25 9.75h4.5M5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v8.25a2.25 2.25 0 0 1-2.25 2.25H8.25L3.75 21v-4.5A2.25 2.25 0 0 1 3 14.25V6a2.25 2.25 0 0 1 2.25-2.25Z"/></svg>
        {{ $recipe->comments_count }} {{ \Illuminate\Support\Str::plural('comment', $recipe->comments_count) }}
    </a>

    <span data-share-status role="status" aria-live="polite" class="text-xs text-slate-500"></span>
</div>

<div class="flex items-center justify-between gap-2 border-t border-slate-100 px-5 py-3">
    <a href="{{ route('recipes.show', $recipe) }}"
       class="text-sm font-semibold text-orange-700 hover:text-orange-800">
        View recipe <span aria-hidden="true">→</span>
    </a>
    @auth
        @if (auth()->id() === $recipe->user_id)
            <div class="flex items-center gap-2">
                <a href="{{ route('recipes.edit', $recipe) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-orange-300 hover:bg-orange-50">Edit</a>
                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" onsubmit="return confirm('Delete this recipe? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
                </form>
            </div>
        @endif
    @endauth
</div>
