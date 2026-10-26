<?php

use App\Models\Recipe;
use App\Models\RecipeFavorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('curated recipe cards open a detail page', function () {
    $this->get(route('recipes.public-category', 'chicken'))
        ->assertOk()
        ->assertSee(route('recipes.curated.show', ['chicken', 'lemon-herb-roast-chicken']));

    $this->get(route('recipes.curated.show', ['chicken', 'lemon-herb-roast-chicken']))
        ->assertOk()
        ->assertSee('Lemon Herb Roast Chicken')
        ->assertSee('Golden roasted chicken with bright lemon, garlic, and garden herbs.')
        ->assertSee('Shared by KitchHug Kitchen');
});

test('signed-in users can save and remove curated recipe favorites', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post(route('favorites.toggle'), [
        'category' => 'chicken',
        'recipe_slug' => 'lemon-herb-roast-chicken',
    ])->assertRedirect();

    expect(RecipeFavorite::query()->where('user_id', $user->id)->count())->toBe(1);

    $this->get(route('favorites.index'))
        ->assertOk()
        ->assertSee('Lemon Herb Roast Chicken')
        ->assertSee(route('recipes.curated.show', ['chicken', 'lemon-herb-roast-chicken']));

    $this->post(route('favorites.toggle'), [
        'category' => 'chicken',
        'recipe_slug' => 'lemon-herb-roast-chicken',
    ])->assertRedirect();

    expect(RecipeFavorite::query()->where('user_id', $user->id)->count())->toBe(0);
});

test('users can save other cooks recipes and find them from the header favorites page', function () {
    $author = User::factory()->create(['name' => 'Recipe Owner']);
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Community Stew',
        'category' => 'Dinner',
        'description' => 'A hearty community recipe.',
        'ingredients' => 'Beans and vegetables',
        'instructions' => 'Simmer until tender.',
        'prep_time_minutes' => 45,
        'image_path' => 'recipe-images/community-stew.jpg',
    ]);

    $this->actingAs($user)
        ->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('name="recipe_id"', false)
        ->assertSee('Save Recipe');

    $this->post(route('favorites.toggle'), ['recipe_id' => $recipe->id])
        ->assertRedirect()
        ->assertSessionHas('status', 'Recipe added to favorites.');

    $this->get(route('favorites.index'))
        ->assertOk()
        ->assertSee('Community Stew')
        ->assertSee(route('recipes.show', $recipe))
        ->assertSee('href="'.route('favorites.index').'"', false)
        ->assertSee('>1</span>', false);

    $this->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('♥ Saved to Favorites');

    $this->post(route('favorites.toggle'), ['recipe_id' => $recipe->id])
        ->assertRedirect()
        ->assertSessionHas('status', 'Recipe removed from favorites.');

    expect(RecipeFavorite::query()->where('user_id', $user->id)->count())->toBe(0);
});
