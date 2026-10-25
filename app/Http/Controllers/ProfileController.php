<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $recipes = $user->recipes()->latest()->get();

        return view('profile.show', compact('user', 'recipes'));
    }
}
