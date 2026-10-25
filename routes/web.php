<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeFavoriteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('user_homepage')
        : view('welcome');
})->name('home');

// Login at Register Routes gamit ang tamang view path
Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/register', 'pages.auth.register')->name('register');

Route::get('/recipes/featured/{slug}', [RecipeController::class, 'featured'])->name('recipes.featured');
Route::get('/users/{user}', [ProfileController::class, 'publicProfile'])->name('users.show');
Route::get('/explore/categories/{category}/{recipeSlug}', [RecipeController::class, 'categoryRecipe'])
    ->whereIn('category', ['chicken', 'seafood', 'beef', 'pasta', 'dips-spreads', 'dessert', 'pork', 'side-dish'])
    ->name('recipes.curated.show');
Route::get('/explore/categories/{category}', [RecipeController::class, 'category'])
    ->middleware('guest')
    ->whereIn('category', ['chicken', 'seafood', 'beef', 'pasta', 'dips-spreads', 'dessert', 'pork', 'side-dish'])
    ->name('recipes.public-category');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/user_homepage', 'user_homepage')->name('user_homepage');
    Route::view('/my-recipes', 'my_recipes')->name('my_recipes');
    Route::get('/favorite-recipes', [RecipeFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorite-recipes/toggle', [RecipeFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::view('/videos', 'videos')->name('videos');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::post('/users/{user}/follow', [ProfileController::class, 'toggleFollow'])->name('users.follow');
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
    // Keep a friendly alias for the same page, but the real redirect target is /user_homepage
    Route::view('/home', 'user_homepage');
    Route::redirect('/dashboard', '/user_homepage');
});

require __DIR__.'/settings.php';
