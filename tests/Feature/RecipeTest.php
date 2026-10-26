<?php

use App\Models\Recipe;
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
        ->assertSee(route('search.index'))
        ->assertSee('aria-label="Search"', false)
        ->assertSee('Explore Recipes')
        ->assertSee('Crispy Chicken')
        ->assertSee('Fluffy Pancakes')
        ->assertSee('Garlic Cream Pasta')
        ->assertSee('Fresh Garden Salad')
        ->assertSee(route('recipes.featured', 'crispy-chicken'))
        ->assertSee(route('recipes.public-category', 'chicken'));
});

test('welcome page separates community images and videos into tabs', function () {
    $author = User::factory()->create(['name' => 'Home Cook']);
    $commenter = User::factory()->create([
        'name' => 'Commenting Cook',
        'profile_photo_path' => 'profile-photos/commenting-cook.jpg',
    ]);
    $recipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Home Page Recipe',
        'category' => 'Dinner',
        'description' => 'A recipe shared on the home page.',
        'ingredients' => 'Rice and vegetables',
        'instructions' => 'Cook and serve.',
        'prep_time_minutes' => 25,
        'image_path' => 'recipe-images/home-page-recipe.jpg',
        'video_path' => 'recipe-videos/home-page-recipe.mp4',
    ]);
    $recipe->comments()->create([
        'user_id' => $commenter->id,
        'body' => 'This looks delicious.',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('role="tablist" aria-label="Home recipe media"', false)
        ->assertSee('id="home-images-panel" role="tabpanel"', false)
        ->assertSee('id="home-videos-panel" role="tabpanel"', false)
        ->assertSee('id="home-videos-panel" role="tabpanel" aria-labelledby="home-videos-tab" data-community-media-panel="videos" class="pt-8" hidden', false)
        ->assertSee('mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3', false)
        ->assertSee('Home Page Recipe')
        ->assertSee('http://localhost:8000/storage/recipe-images/home-page-recipe.jpg')
        ->assertSee('http://localhost:8000/storage/recipe-videos/home-page-recipe.mp4')
        ->assertSee('This looks delicious.')
        ->assertSee('Commenting Cook')
        ->assertSee('http://localhost:8000/storage/profile-photos/commenting-cook.jpg')
        ->assertSee(route('recipes.show', $recipe));
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

test('recipe category pages include uploaded community recipes matching recipe type', function () {
    $viewer = User::factory()->create();
    $author = User::factory()->create(['name' => 'Chicken Recipe Author']);
    $chickenRecipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Community Crispy Chicken',
        'category' => 'Dinner',
        'method' => 'Frying',
        'recipe_type' => 'Chicken Recipes',
        'description' => 'A crispy chicken recipe shared by a home cook.',
        'ingredients' => 'Chicken and breadcrumbs',
        'instructions' => 'Fry until golden.',
        'prep_time_minutes' => 30,
        'image_path' => 'recipe-images/community-chicken.jpg',
        'video_path' => 'recipe-videos/community-chicken.mp4',
    ]);
    Recipe::create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Community Side Salad',
        'category' => 'Lunch',
        'method' => 'Boiling',
        'recipe_type' => 'Side Dish',
        'description' => 'A side dish shared by a home cook.',
        'ingredients' => 'Lettuce',
        'instructions' => 'Mix together.',
        'prep_time_minutes' => 15,
        'image_path' => 'recipe-images/community-side.jpg',
    ]);

    $this->actingAs($viewer)
        ->get(route('recipes.category', 'chicken'))
        ->assertOk()
        ->assertSee('Community Crispy Chicken')
        ->assertSee('Chicken Recipe Author')
        ->assertSee(route('recipes.show', $chickenRecipe))
        ->assertDontSee('Community Side Salad');

    $this->get(route('recipes.category', 'side-dish'))
        ->assertOk()
        ->assertSee('Community Side Salad')
        ->assertDontSee('Community Crispy Chicken');
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

test('homepage shows recently shared recipes from all users', function () {
    $viewer = User::factory()->create(['name' => 'Recipe Viewer']);
    $author = User::factory()->create(['name' => 'Community Cook']);
    $recipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Community Pancakes',
        'category' => 'Breakfast',
        'description' => 'Fluffy pancakes for everyone.',
        'ingredients' => 'Flour and milk',
        'instructions' => 'Mix and cook.',
        'prep_time_minutes' => 20,
        'image_path' => 'recipe-images/community-pancakes.jpg',
        'video_path' => 'recipe-videos/community-pancakes.mp4',
    ]);
    $commenter = User::factory()->create([
        'name' => 'Pancake Fan',
        'profile_photo_path' => 'profile-photos/pancake-fan.jpg',
    ]);
    $recipe->comments()->create([
        'user_id' => $commenter->id,
        'body' => 'I want to try this recipe.',
    ]);

    $this->actingAs($viewer)
        ->get(route('user_homepage'))
        ->assertOk()
        ->assertSee('Welcome back, Recipe Viewer!')
        ->assertSee('role="tablist" aria-label="Community recipe media"', false)
        ->assertSee('id="community-images-tab" type="button" role="tab"', false)
        ->assertSee('aria-selected="true" tabindex="0" data-community-media-tab="images"', false)
        ->assertSee('aria-selected="false" tabindex="-1" data-community-media-tab="videos"', false)
        ->assertSee('id="community-videos-panel" role="tabpanel"', false)
        ->assertSee('data-community-media-panel="videos" class="mt-6" hidden', false)
        ->assertSee('id="community-images-heading"', false)
        ->assertSee('id="community-videos-heading"', false)
        ->assertSee('mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3', false)
        ->assertSee('Community Pancakes')
        ->assertSee('Community Cook')
        ->assertSee(route('recipes.show', $recipe))
        ->assertSee(route('users.show', $author))
        ->assertSee('<img src="http://localhost:8000/storage/recipe-images/community-pancakes.jpg"', false)
        ->assertSee('<video controls', false)
        ->assertSee('I want to try this recipe.')
        ->assertSee('Pancake Fan')
        ->assertSee('http://localhost:8000/storage/profile-photos/pancake-fan.jpg')
        ->assertSee('http://localhost:8000/storage/recipe-videos/community-pancakes.mp4');

    $this->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('Community Pancakes')
        ->assertSee('Flour and milk')
        ->assertSee('Mix and cook.');
});

test('search finds recipes from other cooks and users by name', function () {
    $author = User::factory()->create(['name' => 'Noodle Chef']);
    $recipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Spicy Noodle Bowl',
        'category' => 'Dinner',
        'description' => 'A quick and spicy meal.',
        'ingredients' => 'Noodles and chili',
        'instructions' => 'Boil and season.',
        'prep_time_minutes' => 25,
        'image_path' => 'recipe-images/noodle-bowl.jpg',
    ]);
    Recipe::create([
        'user_id' => User::factory()->create(['name' => 'Salad Cook'])->id,
        'title' => 'Garden Salad',
        'category' => 'Lunch',
        'description' => 'A fresh green salad.',
        'ingredients' => 'Lettuce',
        'instructions' => 'Toss together.',
        'prep_time_minutes' => 10,
        'image_path' => 'recipe-images/garden-salad.jpg',
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('search.index', ['search' => 'noodle']))
        ->assertOk()
        ->assertSee('action="'.route('search.index').'"', false)
        ->assertSee('name="search"', false)
        ->assertSee('value="noodle"', false)
        ->assertSee('type="submit"', false)
        ->assertSee('Spicy Noodle Bowl')
        ->assertSee('View full recipe')
        ->assertSee('Noodle Chef')
        ->assertSee(route('recipes.show', $recipe))
        ->assertDontSee('Garden Salad');

    $this->get(route('search.index', ['search' => 'chef']))
        ->assertOk()
        ->assertSee('Noodle Chef')
        ->assertSee(route('users.show', $author))
        ->assertDontSee('Salad Cook');
});

test('method and course menus show uploaded recipes from the community', function () {
    $author = User::factory()->create(['name' => 'Community Cook']);
    $bakedRecipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Baked Sunday Pie',
        'category' => 'Dessert',
        'method' => 'Baking',
        'recipe_type' => 'Dessert',
        'description' => 'A warm pie from the community.',
        'ingredients' => 'Apples and flour',
        'instructions' => 'Bake until golden.',
        'prep_time_minutes' => 55,
        'image_path' => 'recipe-images/sunday-pie.jpg',
    ]);
    Recipe::create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Grilled Lunch',
        'category' => 'Lunch',
        'method' => 'Grilling',
        'recipe_type' => 'Chicken Recipes',
        'description' => 'A grilled lunch recipe.',
        'ingredients' => 'Chicken',
        'instructions' => 'Grill until cooked.',
        'prep_time_minutes' => 35,
        'image_path' => 'recipe-images/grilled-lunch.jpg',
    ]);

    $this->get(route('recipes.by-method', 'baking'))
        ->assertOk()
        ->assertSee('Baked Sunday Pie')
        ->assertSee('Community Cook')
        ->assertSee(route('recipes.show', $bakedRecipe))
        ->assertDontSee('Grilled Lunch');

    $this->get(route('recipes.by-course', 'lunch'))
        ->assertOk()
        ->assertSee('Grilled Lunch')
        ->assertDontSee('Baked Sunday Pie');

    $this->get(route('search.index', ['search' => 'baking']))
        ->assertOk()
        ->assertSee('Baked Sunday Pie')
        ->assertDontSee('Grilled Lunch');

    $this->get(route('search.index', ['search' => 'dessert']))
        ->assertOk()
        ->assertSee('Baked Sunday Pie')
        ->assertDontSee('Grilled Lunch');
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

test('uploaded recipe video appears with native playback controls', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'user_id' => $user->id,
        'title' => 'Garden Pasta',
        'category' => 'Dinner',
        'description' => 'Fresh herbs and vegetables.',
        'ingredients' => 'Pasta and tomatoes',
        'instructions' => 'Boil pasta and mix with vegetables.',
        'prep_time_minutes' => 35,
        'image_path' => 'recipe-images/garden-pasta.jpg',
        'video_path' => 'recipe-videos/garden-pasta.mp4',
    ]);

    $this->actingAs($user)
        ->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('id="recipe-scroll-area" class="min-w-0 lg:col-span-1 lg:h-full lg:overflow-y-auto', false)
        ->assertSee('data-recipe-comments-panel', false)
        ->assertSee('data-recipe-video-player', false)
        ->assertSee('<video controls', false)
        ->assertDontSee('data-video-sound-on', false)
        ->assertSee('aria-labelledby="recipe-photo-heading"', false)
        ->assertSee('aria-labelledby="recipe-video-heading"', false)
        ->assertSee('<img src="http://localhost:8000/storage/recipe-images/garden-pasta.jpg" alt="Recipe photo for Garden Pasta"', false)
        ->assertDontSee('poster="http://localhost:8000/storage/recipe-images/garden-pasta.jpg"', false)
        ->assertSee('http://localhost:8000/storage/recipe-videos/garden-pasta.mp4', false);
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
        ->assertSee('Dessert')
        ->assertSee('data-navigation-dropdown', false);
});

test('guests must sign in before browsing cooking videos', function () {
    $this->get(route('videos'))->assertRedirect(route('login'));
});
