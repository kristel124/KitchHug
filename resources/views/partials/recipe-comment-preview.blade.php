<a href="{{ route('recipes.show', $recipe) }}#comments" class="inline-flex items-center gap-1.5 border-t border-slate-100 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:text-orange-700">
    <svg viewBox="0 0 24 24" fill="none" class="size-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.25 6.75h7.5M8.25 9.75h4.5M5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v8.25a2.25 2.25 0 0 1-2.25 2.25H8.25L3.75 21v-4.5A2.25 2.25 0 0 1 3 14.25V6a2.25 2.25 0 0 1 2.25-2.25Z"/></svg>
    {{ $recipe->comments_count }} {{ \Illuminate\Support\Str::plural('comment', $recipe->comments_count) }}
</a>
