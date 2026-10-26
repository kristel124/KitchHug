<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\RecipeActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:60'],
            'method' => ['nullable', 'string', 'max:60'],
            'recipe_type' => ['nullable', 'string', 'max:60'],
            'sort' => ['nullable', 'in:newest,oldest,title'],
        ]);
        $recipeQuery = $user->recipes()->withReactionSummary($request->user()?->id)->with('user');
        $totalRecipeCount = (clone $recipeQuery)->count();
        $categories = (clone $recipeQuery)->select('category')->distinct()->orderBy('category')->pluck('category');
        $methods = (clone $recipeQuery)->whereNotNull('method')->select('method')->distinct()->orderBy('method')->pluck('method');
        $recipeTypes = (clone $recipeQuery)->whereNotNull('recipe_type')->select('recipe_type')->distinct()->orderBy('recipe_type')->pluck('recipe_type');
        $search = trim($filters['search'] ?? '');

        $recipes = $recipeQuery
            ->when($search !== '', fn ($query) => $query->whereRaw('LOWER(title) LIKE ?', ['%'.Str::lower($search).'%']))
            ->when(isset($filters['category']), fn ($query) => $query->where('category', $filters['category']))
            ->when(isset($filters['method']), fn ($query) => $query->where('method', $filters['method']))
            ->when(isset($filters['recipe_type']), fn ($query) => $query->where('recipe_type', $filters['recipe_type']))
            ->when(($filters['sort'] ?? 'newest') === 'oldest', fn ($query) => $query->orderBy('created_at')->orderBy('id'))
            ->when(($filters['sort'] ?? 'newest') === 'title', fn ($query) => $query->orderByRaw('LOWER(title)')->orderBy('id'))
            ->when(($filters['sort'] ?? 'newest') === 'newest', fn ($query) => $query->orderByDesc('created_at')->orderByDesc('id'))
            ->get();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('profile.show', compact(
            'user',
            'recipes',
            'totalRecipeCount',
            'categories',
            'methods',
            'recipeTypes',
            'filters',
            'search',
            'followersCount',
            'followingCount',
        ));
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $oldPhotoPath = $user->profile_photo_path;
        $newPhotoPath = $validated['profile_photo']->store('profile-photos', 'public');
        $user->profile_photo_path = $newPhotoPath;
        $user->save();

        if ($oldPhotoPath !== null) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return to_route('profile.edit')->with('status', 'Your profile photo was updated.');
    }

    public function publicProfile(Request $request, User $user): View
    {
        $viewer = $request->user();
        $recipes = $user->recipes()->withReactionSummary($viewer?->id)->latest()->get();

        return view('profile.public', [
            'user' => $user,
            'recipes' => $recipes,
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'isFollowing' => $viewer instanceof User && $viewer->id !== $user->id
                ? $viewer->following()->whereKey($user->id)->exists()
                : false,
            'isOwnProfile' => $viewer instanceof User && $viewer->id === $user->id,
        ]);
    }

    public function toggleFollow(Request $request, User $user): RedirectResponse
    {
        $follower = $request->user();
        abort_unless($follower instanceof User, 403);
        abort_if($follower->is($user), 403, 'You cannot follow your own account.');

        if ($follower->following()->whereKey($user->id)->exists()) {
            $follower->following()->detach($user->id);

            return back()->with('status', 'You unfollowed '.$user->name.'.');
        }

        $changes = $follower->following()->syncWithoutDetaching([$user->id]);

        if (in_array($user->id, $changes['attached'], true)) {
            $user->notify(new RecipeActivityNotification('new_follower', $follower));
        }

        return back()->with('status', 'You are now following '.$user->name.'.');
    }
}
