<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeFavorite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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

        $favorites = $user->recipeFavorites()->with('recipe.user')->latest()->get();
        $categories = RecipeController::categoryCatalog();
        $recipes = $favorites->whereNull('recipe_id')->map(function (RecipeFavorite $favorite) use ($categories): array {
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
        $communityRecipes = $favorites
            ->whereNotNull('recipe_id')
            ->pluck('recipe')
            ->filter();

        return view('my_recipes', compact('recipes', 'communityRecipes'));
    }

    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $data = $request->validate([
            'recipe_id' => ['nullable', 'integer', 'required_without:category', 'exists:recipes,id'],
            'category' => ['nullable', 'required_without:recipe_id', 'string', 'max:50'],
            'recipe_slug' => ['nullable', 'required_with:category', 'string', 'max:120'],
        ]);

        if (isset($data['recipe_id'])) {
            $recipe = Recipe::query()->findOrFail($data['recipe_id']);
            $favorite = $user->recipeFavorites()->where('recipe_id', $recipe->id)->first();

            if ($favorite) {
                $favorite->delete();
                $saved = false;
            } else {
                $user->recipeFavorites()->create(['recipe_id' => $recipe->id]);
                $saved = true;
            }

            if ($request->header('X-Requested-With') === 'fetch') {
                return response()->json([
                    'saved' => $saved,
                    'count' => $recipe->recipeFavorites()->count(),
                ]);
            }

            return back()->with('status', $saved ? 'Recipe added to favorites.' : 'Recipe removed from favorites.');
        }

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
            $saved = false;
        } else {
            $user->recipeFavorites()->create($data);
            $saved = true;
        }

        if ($request->header('X-Requested-With') === 'fetch') {
            return response()->json(['saved' => $saved]);
        }

        return back()->with('status', $saved ? 'Recipe added to favorites.' : 'Recipe removed from favorites.');
    }
}
