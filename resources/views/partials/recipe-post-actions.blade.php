<div class="flex flex-wrap items-center gap-3 border-t border-slate-100 px-5 py-3">
    @auth
        @if (auth()->id() === $recipe->user_id)
            <span class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-rose-700">
                <span aria-hidden="true">♥</span>
                <span>{{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }}</span>
            </span>
        @else
            <form method="POST" action="{{ route('recipes.reactions.toggle', $recipe) }}">
                @csrf
                <button type="submit" aria-label="{{ $recipe->is_liked ? 'Remove heart reaction' : 'React with a heart' }}" aria-pressed="{{ $recipe->is_liked ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition {{ $recipe->is_liked ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-slate-50 text-slate-700 hover:bg-rose-50 hover:text-rose-700' }}">
                    <span aria-hidden="true">{{ $recipe->is_liked ? '♥' : '♡' }}</span>
                    <span>{{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }}</span>
                </button>
            </form>
        @endif
    @else
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-rose-50 hover:text-rose-700">
            <span aria-hidden="true">♡</span>
            <span>{{ $recipe->reactions_count }} {{ \Illuminate\Support\Str::plural('heart', $recipe->reactions_count) }} · Log in to react</span>
        </a>
    @endauth

    <div class="ml-auto">
        <button type="button" data-share-recipe data-share-url="{{ route('recipes.show', $recipe) }}" data-share-title="{{ $recipe->title }}" data-share-text="{{ $recipe->description }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-700">
            Share post
        </button>
        <span data-share-status role="status" aria-live="polite" class="ml-2 text-xs text-slate-500"></span>
    </div>
</div>
