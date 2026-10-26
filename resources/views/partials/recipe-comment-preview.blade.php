<div class="border-t border-slate-100 bg-slate-50/70 px-5 py-4">
    <div class="flex items-center justify-between gap-3">
        <h4 class="text-sm font-bold text-slate-800">Comments</h4>
        <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $recipe->comments_count }}</span>
    </div>

    @if ($recipe->comments->isNotEmpty())
        <div class="mt-3 space-y-3">
            @foreach ($recipe->comments as $comment)
                <div class="flex items-start gap-3">
                    <a href="{{ route('users.show', $comment->user) }}" class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full bg-orange-100 text-xs font-bold text-orange-800" aria-label="{{ $comment->user->name }}'s profile">
                        @if ($comment->user->profile_photo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($comment->user->profile_photo_path) }}" alt="{{ $comment->user->name }} profile photo" class="h-full w-full object-cover">
                        @else
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($comment->user->name, 0, 1)) }}
                        @endif
                    </a>
                    <div class="min-w-0 flex-1 rounded-xl bg-white px-3 py-2">
                        <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1">
                            <a href="{{ route('users.show', $comment->user) }}" class="text-xs font-bold text-slate-800 hover:text-orange-700">{{ $comment->user->name }}</a>
                            <time class="text-[11px] text-slate-400" datetime="{{ $comment->created_at->toISOString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="mt-1 whitespace-pre-line break-words text-sm leading-5 text-slate-600">{{ $comment->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-2 text-sm text-slate-500">No comments yet. Start the conversation.</p>
    @endif

    <a href="{{ route('recipes.show', $recipe) }}#comments" class="mt-3 inline-flex text-xs font-semibold text-orange-700 hover:text-orange-800">
        @if ($recipe->comments_count > 2)
            View all {{ $recipe->comments_count }} comments
        @elseif (auth()->check())
            Add a comment
        @else
            Sign in to comment
        @endif
        <span class="ml-1" aria-hidden="true">→</span>
    </a>
</div>
