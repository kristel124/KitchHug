<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeReaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RecipeReactionController extends Controller
{
    public function index(Request $request, Recipe $recipe): Response
    {
        $users = $recipe->reactions()->with('user')->latest()->get()
            ->map(fn ($reaction) => [
                'name' => $reaction->user?->name ?? 'Unknown',
                'initials' => $reaction->user?->initials() ?? '?',
                'profile_photo_path' => $reaction->user?->profile_photo_path,
                'url' => $reaction->user ? route('users.show', $reaction->user) : null,
                'reacted_at' => $reaction->created_at->diffForHumans(),
            ]);

        return response(view('partials.reaction-list', ['users' => $users]));
    }

    public function toggle(Request $request, Recipe $recipe): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $reaction = $recipe->reactions()->where('user_id', $user->id)->first();

        if ($reaction instanceof RecipeReaction) {
            $reaction->delete();

            return back()->with('status', 'Heart reaction removed.');
        }

        $recipe->reactions()->create(['user_id' => $user->id]);

        return back()->with('status', 'You hearted this recipe.');
    }
}
