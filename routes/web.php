<?php

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeBrowseController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeFavoriteController;
use App\Http\Controllers\RecipeReactionController;
use App\Http\Controllers\SearchController;
use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'user_homepage');
    }

    return view('welcome', [
        'homeImageRecipes' => Recipe::query()
            ->withReactionSummary(auth()->id())
            ->with([
                'user',
                'comments' => fn ($query) => $query->with('user')->latest()->limit(2),
            ])
            ->withCount('comments')
            ->latest()
            ->take(12)
            ->get(),
        'homeVideoRecipes' => Recipe::query()
            ->withReactionSummary(auth()->id())
            ->with([
                'user',
                'comments' => fn ($query) => $query->with('user')->latest()->limit(2),
            ])
            ->withCount('comments')
            ->whereNotNull('video_path')
            ->latest()
            ->take(12)
            ->get(),
    ]);
})->name('home');

// Login at Register Routes gamit ang tamang view path
Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/register', 'pages.auth.register')->name('register');

Route::get('/recipes/featured/{slug}', [RecipeController::class, 'featured'])->name('recipes.featured');
Route::get('/users/{user}', [ProfileController::class, 'publicProfile'])->name('users.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/recipes/method/{method}', [RecipeBrowseController::class, 'byMethod'])
    ->name('recipes.by-method');
Route::get('/recipes/course/{course}', [RecipeBrowseController::class, 'byCourse'])
    ->name('recipes.by-course');
Route::get('/explore/categories/{category}/{recipeSlug}', [RecipeController::class, 'categoryRecipe'])
    ->whereIn('category', ['chicken', 'seafood', 'beef', 'pasta', 'dips-spreads', 'dessert', 'pork', 'side-dish'])
    ->name('recipes.curated.show');
Route::get('/explore/categories/{category}', [RecipeController::class, 'category'])
    ->middleware('guest')
    ->whereIn('category', ['chicken', 'seafood', 'beef', 'pasta', 'dips-spreads', 'dessert', 'pork', 'side-dish'])
    ->name('recipes.public-category');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user_homepage', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('user_homepage', [
            'imageRecipes' => Recipe::query()
                ->withReactionSummary(auth()->id())
                ->with([
                    'user',
                    'comments' => fn ($query) => $query->with('user')->latest()->limit(2),
                ])
                ->withCount('comments')
                ->latest()
                ->take(12)
                ->get(),
            'videoRecipes' => Recipe::query()
                ->withReactionSummary(auth()->id())
                ->with([
                    'user',
                    'comments' => fn ($query) => $query->with('user')->latest()->limit(2),
                ])
                ->withCount('comments')
                ->whereNotNull('video_path')
                ->latest()
                ->take(12)
                ->get(),
        ]);
    })->name('user_homepage');
    Route::get('/admin', function () {
        // Merge recent recipes and comments into a unified activity feed
        $recentRecipes = Recipe::query()
            ->with('user')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($recipe) => [
                'type' => 'recipe',
                'user' => $recipe->user,
                'label' => 'shared a recipe',
                'detail' => $recipe->title,
                'happened_at' => $recipe->created_at,
            ]);

        $recentComments = RecipeComment::query()
            ->with(['user', 'recipe'])
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($comment) => [
                'type' => 'comment',
                'user' => $comment->user,
                'label' => 'commented on a recipe',
                'detail' => $comment->recipe?->title ?? 'a recipe',
                'happened_at' => $comment->created_at,
            ]);

        $activityLog = $recentRecipes
            ->concat($recentComments)
            ->sortByDesc('happened_at')
            ->take(20)
            ->values();

        return view('admin.dashboard', [
            'userCount' => User::count(),
            'recipeCount' => Recipe::count(),
            'commentCount' => RecipeComment::count(),
            'activePostersRate' => User::count() > 0
                ? round((User::has('recipes')->count() / User::count()) * 100)
                : 0,
            'activityLog' => $activityLog,
        ]);
    })->middleware('can:access-admin-dashboard')->name('admin.dashboard');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.users.index');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.users.destroy');
    Route::get('/admin/recipes', [AdminRecipeController::class, 'index'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.recipes.index');
    Route::get('/admin/recipes/{recipe}', [AdminRecipeController::class, 'show'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.recipes.show');
    Route::delete('/admin/recipes/{recipe}', [AdminRecipeController::class, 'destroy'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.recipes.destroy');
    Route::get('/admin/comments', [AdminCommentController::class, 'index'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.comments.index');
    Route::delete('/admin/comments/{comment}', [AdminCommentController::class, 'destroy'])
        ->middleware('can:access-admin-dashboard')
        ->name('admin.comments.destroy');
    Route::view('/my-recipes', 'my_recipes')->name('my_recipes');
    Route::get('/favorite-recipes', [RecipeFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorite-recipes/toggle', [RecipeFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/recipes/{recipe}/heart', [RecipeReactionController::class, 'toggle'])->whereNumber('recipe')->name('recipes.reactions.toggle');
    Route::view('/videos', 'videos')->name('videos');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('users.follow');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/open', [NotificationController::class, 'open'])->whereUuid('notification')->name('notifications.open');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::post('/recipes/featured/{slug}/comments', [RecipeController::class, 'storeFeaturedComment'])->name('recipes.featured.comments.store');
    Route::get('/recipes/{category}', [RecipeController::class, 'category'])
        ->whereIn('category', ['chicken', 'seafood', 'beef', 'pasta', 'dips-spreads', 'dessert', 'pork', 'side-dish'])
        ->name('recipes.category');
    Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->whereNumber('recipe')->name('recipes.show');
    Route::post('/recipes/{recipe}/comments', [RecipeController::class, 'storeComment'])->whereNumber('recipe')->name('recipes.comments.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::redirect('/home', '/user_homepage');
    Route::redirect('/dashboard', '/user_homepage');
});

require __DIR__.'/settings.php';
