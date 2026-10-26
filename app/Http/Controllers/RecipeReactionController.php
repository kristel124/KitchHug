<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeReaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecipeReactionController extends Controller
{
    public function toggle(Request $request, Recipe $recipe): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);
        abort_if($recipe->user_id === $user->id, 403, 'You cannot react to your own recipe.');

        $reaction = $recipe->reactions()->where('user_id', $user->id)->first();

        if ($reaction instanceof RecipeReaction) {
            $reaction->delete();

            return back()->with('status', 'Heart reaction removed.');
        }

        $recipe->reactions()->create(['user_id' => $user->id]);

        return back()->with('status', 'You hearted this recipe.');
    }
}
