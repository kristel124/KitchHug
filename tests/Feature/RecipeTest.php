<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('welcome page shows featured recipe cards below the hero', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Explore Recipes')
        ->assertSee('Crispy Chicken')
        ->assertSee('Fluffy Pancakes')
        ->assertSee('Garlic Cream Pasta')
        ->assertSee('Fresh Garden Salad')
        ->assertSee(route('recipes.featured', 'crispy-chicken'))
        ->assertSee(route('recipes.public-category', 'chicken'));
});

test('each recipe category has a page', function () {
    $categories = [
        'chicken' => 'Chicken Recipes',
        'seafood' => 'Seafood Recipes',
        'beef' => 'Beef Recipes',
        'pasta' => 'Pasta Recipes',
        'dips-spreads' => 'Dips & Spreads',
        'dessert' => 'Dessert',
        'pork' => 'Pork Recipes',
        'side-dish' => 'Side Dish',
    ];

    foreach ($categories as $slug => $title) {
        $this->get(route('recipes.public-category', $slug))
            ->assertOk()
            ->assertSee($title)
            ->assertSee('3 recipes')
            ->assertSee('Recipe collection')
            ->assertSee('Made with love')
            ->assertSee('Get Started')
            ->assertSee('<img src="https://images.unsplash.com/', false);
    }
});

test('unknown recipe categories return not found', function () {
    $this->get(route('recipes.public-category', 'not-a-category'))->assertNotFound();
});

test('signed-in recipe categories remain behind authentication', function () {
    $this->get('/recipes/chicken')->assertRedirect(route('login'));

    $this->actingAs(User::factory()->make())
        ->get(route('recipes.category', 'chicken'))
        ->assertOk()
        ->assertSee('Chicken Recipes');

    $this->get(route('recipes.public-category', 'chicken'))
        ->assertRedirect(route('home'));
});

test('featured recipe cards open a page with recipe information', function () {
    $this->actingAs(User::factory()->make());

    $this->get(route('user_homepage'))
        ->assertOk()
        ->assertSee(route('recipes.featured', 'crispy-chicken'));

    $this->get(route('recipes.featured', 'crispy-chicken'))
        ->assertOk()
        ->assertSee('Crispy Chicken')
        ->assertSee('https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&amp;fit=crop&amp;w=900&amp;q=80', false)
        ->assertSee('Ingredients')
        ->assertSee('Instructions')
        ->assertSee('breadcrumbs');
});

test('guests can view featured recipe information but must sign in or register to comment', function () {
    $this->get(route('recipes.featured', 'crispy-chicken'))
        ->assertOk()
        ->assertSee('Crispy Chicken')
        ->assertSee('Ingredients')
        ->assertSee('Instructions')
        ->assertSee('Want to share your thoughts?')
        ->assertSee(route('login'))
        ->assertSee(route('register'))
        ->assertDontSee('Post comment')
        ->assertSee(route('home'));
});

test('authenticated users can browse cooking videos', function () {
    $this->actingAs(User::factory()->make());

    $this->get(route('videos'))
        ->assertOk()
        ->assertSee('Cooking Videos')
        ->assertSee('Knife Skills: The Basics')
        ->assertSee('Watch on YouTube')
        ->assertSee(route('videos'));
});

test('site navigation does not show my recipes or recipes cooked', function () {
    $this->actingAs(User::factory()->make());

    $this->get(route('user_homepage'))
        ->assertOk()
        ->assertDontSee('My Recipes')
        ->assertDontSee('Recipes Cooked');
});

test('authenticated users can open the favorite recipes page from navigation', function () {
    $this->actingAs(User::factory()->make());

    $this->get(route('favorites.index'))
        ->assertOk()
        ->assertSee('Favorite Recipes')
        ->assertSee('Warm Chicken Noodle')
        ->assertSee(route('favorites.index'));
});

test('guests must sign in before browsing favorite recipes', function () {
    $this->get(route('favorites.index'))->assertRedirect(route('login'));
});

test('site navigation includes a more menu with cooking tips and course', function () {
    $this->actingAs(User::factory()->make());

    $this->get(route('user_homepage'))
        ->assertOk()
        ->assertSee('More')
        ->assertSee('Cooking Tips')
        ->assertSee('Course')
        ->assertSee('Breakfast')
        ->assertSee('Lunch')
        ->assertSee('Dinner')
        ->assertSee('Appetizers')
        ->assertSee('Dessert');
});

test('guests must sign in before browsing cooking videos', function () {
    $this->get(route('videos'))->assertRedirect(route('login'));
});
