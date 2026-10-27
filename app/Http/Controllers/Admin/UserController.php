<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all registered users.
     */
    public function index(): View
    {
        $users = User::query()
            ->where('is_admin', false)
            ->withCount(['recipes', 'recipeComments'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Delete a user account and all their associated data.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 403, 'You cannot delete your own account.');
        abort_if($user->is_admin, 403, 'Admin accounts cannot be deleted.');

        $user->delete();

        return to_route('admin.users.index')->with('status', "User \"{$user->name}\" has been deleted.");
    }
}
