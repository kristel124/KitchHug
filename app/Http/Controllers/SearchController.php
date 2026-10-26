<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');
        $searchTerm = Str::lower($search);

        $recipes = Recipe::query()
            ->withReactionSummary($request->user()?->id)
            ->with('user')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%'.$searchTerm.'%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%'.$searchTerm.'%'])
                    ->orWhereRaw('LOWER(category) LIKE ?', ['%'.$searchTerm.'%'])
                    ->orWhereRaw('LOWER(method) LIKE ?', ['%'.$searchTerm.'%'])
                    ->orWhereRaw('LOWER(recipe_type) LIKE ?', ['%'.$searchTerm.'%']);
            }))
            ->latest()
            ->paginate(12, ['*'], 'recipes_page')
            ->withQueryString();

        $users = User::query()
            ->withCount('recipes')
            ->when($search !== '', fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ['%'.$searchTerm.'%']))
            ->orderBy('name')
            ->paginate(12, ['*'], 'users_page')
            ->withQueryString();

        return view('search.index', compact('search', 'recipes', 'users'));
    }
}
