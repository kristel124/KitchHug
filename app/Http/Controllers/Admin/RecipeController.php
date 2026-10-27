<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Notifications\RecipeActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Display a listing of all recipes.
     */
    public function index(): View
    {
        $recipes = Recipe::query()
            ->with('user')
            ->withCount('comments')
            ->latest()
            ->paginate(20);

        return view('admin.recipes.index', compact('recipes'));
    }

    /**
     * Display the specified recipe.
     */
    public function show(Recipe $recipe): View
    {
        $recipe->load(['user', 'comments.user']);

        return view('admin.recipes.show', compact('recipe'));
    }

    /**
     * Delete a recipe and notify the owner.
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->load('user');

        if ($recipe->user) {
            $recipe->user->notify(new RecipeActivityNotification('recipe_deleted', auth()->user(), $recipe));
        }

        Storage::disk('public')->delete($recipe->image_path);

        if ($recipe->video_path !== null) {
            Storage::disk('public')->delete($recipe->video_path);
        }

        $recipe->delete();

        return to_route('admin.recipes.index')->with('status', 'Recipe deleted and user notified.');
    }
}
