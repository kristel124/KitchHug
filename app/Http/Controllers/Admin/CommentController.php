<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecipeComment;
use App\Notifications\RecipeActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Display a listing of all recipe comments.
     */
    public function index(): View
    {
        $comments = RecipeComment::query()
            ->with(['user', 'recipe'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Delete a comment and notify the author.
     */
    public function destroy(RecipeComment $comment): RedirectResponse
    {
        $comment->load(['user', 'recipe']);

        if ($comment->user && $comment->recipe) {
            $comment->user->notify(new RecipeActivityNotification('comment_deleted', auth()->user(), $comment->recipe, $comment));
        }

        $redirectRoute = request()->headers->get('referer') && str_contains(request()->headers->get('referer'), 'admin/recipes/')
            ? to_route('admin.recipes.show', $comment->recipe_id)
            : to_route('admin.comments.index');

        $comment->delete();

        return $redirectRoute->with('status', 'Comment deleted and user notified.');
    }
}
