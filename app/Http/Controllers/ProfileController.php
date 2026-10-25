<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $recipes = $user->recipes()->with('user')->latest()->get();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('profile.show', compact('user', 'recipes', 'followersCount', 'followingCount'));
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
        $recipes = $user->recipes()->latest()->get();
        $viewer = $request->user();

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

        $follower->following()->syncWithoutDetaching([$user->id]);

        return back()->with('status', 'You are now following '.$user->name.'.');
    }
}
