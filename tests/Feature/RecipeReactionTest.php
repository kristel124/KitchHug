<?php

use App\Models\Recipe;
use App\Models\RecipeFavorite;
use App\Models\RecipeReaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createReactionTestRecipe(User $author): Recipe
{
    return Recipe::create([
        'user_id' => $author->id,
        'title' => 'Hearted Community Recipe',
        'category' => 'Dinner',
        'description' => 'A recipe to react to.',
        'ingredients' => 'Rice and vegetables',
        'instructions' => 'Cook until ready.',
        'prep_time_minutes' => 30,
        'image_path' => 'recipe-images/hearted-community-recipe.jpg',
    ]);
}

test('signed-in users can heart and remove a heart from another cook recipe', function () {
    $author = User::factory()->create();
    $user = User::factory()->create();
    $recipe = createReactionTestRecipe($author);

    $this->actingAs($user)->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('data-share-recipe', false)
        ->assertSee('data-share-text="A recipe to react to."', false)
        ->assertSee('Share post')
        ->assertSee(route('recipes.show', $recipe), false)
        ->assertSee('React with a heart');

    $this->post(route('recipes.reactions.toggle', $recipe))
        ->assertRedirect()
        ->assertSessionHas('status', 'You hearted this recipe.');

    expect(RecipeReaction::query()->where('recipe_id', $recipe->id)->where('user_id', $user->id)->count())->toBe(1);

    $this->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('Remove heart reaction')
        ->assertSee('1 heart');

    $this->post(route('recipes.reactions.toggle', $recipe))
        ->assertRedirect()
        ->assertSessionHas('status', 'Heart reaction removed.');

    expect(RecipeReaction::query()->where('recipe_id', $recipe->id)->count())->toBe(0);
});

test('heart reactions are separate from saved recipe favorites', function () {
    $author = User::factory()->create();
    $user = User::factory()->create();
    $recipe = createReactionTestRecipe($author);

    $this->actingAs($user)->post(route('recipes.reactions.toggle', $recipe))->assertRedirect();

    expect(RecipeReaction::query()->where('recipe_id', $recipe->id)->count())->toBe(1)
        ->and(RecipeFavorite::query()->where('recipe_id', $recipe->id)->count())->toBe(0);
});

test('guests must sign in before reacting to a recipe', function () {
    $recipe = createReactionTestRecipe(User::factory()->create());

    $this->post(route('recipes.reactions.toggle', $recipe))
        ->assertRedirect(route('login'));

    expect(RecipeReaction::query()->count())->toBe(0);
});

test('home and search recipe cards render share and heart controls', function () {
    $author = User::factory()->create();
    $recipe = createReactionTestRecipe($author);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-share-recipe', false)
        ->assertSee('data-share-text="A recipe to react to."', false)
        ->assertSee('Log in to react');

    $this->get(route('search.index', ['search' => 'Hearted Community Recipe']))
        ->assertOk()
        ->assertSee('data-share-recipe', false)
        ->assertSee(route('recipes.show', $recipe), false);

    $viewer = User::factory()->create();
    $this->actingAs($viewer)->get(route('user_homepage'))
        ->assertOk()
        ->assertSee('data-share-recipe', false)
        ->assertSee('React with a heart');
});
