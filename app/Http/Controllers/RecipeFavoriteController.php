<?php

namespace App\Http\Controllers;

use App\Models\RecipeFavorite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RecipeFavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $categories = RecipeController::categoryCatalog();
        $recipes = $user->recipeFavorites()->latest()->get()->map(function (RecipeFavorite $favorite) use ($categories): array {
            abort_unless(isset($categories[$favorite->category]), 404);

            $recipe = collect($categories[$favorite->category]['recipes'])
                ->first(fn (array $recipe): bool => Str::slug($recipe['name']) === $favorite->recipe_slug);
            abort_unless($recipe !== null, 404);

            return $recipe + [
                'category' => $categories[$favorite->category]['title'],
                'category_slug' => $favorite->category,
                'recipe_slug' => $favorite->recipe_slug,
            ];
        });

        return view('my_recipes', compact('recipes'));
    }

    public function toggle(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $data = $request->validate([
            'category' => ['required', 'string', 'max:50'],
            'recipe_slug' => ['required', 'string', 'max:120'],
        ]);

        $categories = RecipeController::categoryCatalog();
        abort_unless(isset($categories[$data['category']]), 404);
        abort_unless(
            collect($categories[$data['category']]['recipes'])
                ->contains(fn (array $recipe): bool => Str::slug($recipe['name']) === $data['recipe_slug']),
            404
        );

        $favorite = $user->recipeFavorites()
            ->where('category', $data['category'])
            ->where('recipe_slug', $data['recipe_slug'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'Recipe removed from favorites.';
        } else {
            $user->recipeFavorites()->create($data);
            $status = 'Recipe added to favorites.';
        }

        return back()->with('status', $status);
    }
}
