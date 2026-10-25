<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores and displays a comment only on its featured recipe', function () {
    $user = User::factory()->create(['name' => 'Recipe Fan']);
    $body = '<script>alert("xss")</script> Delicious and easy!';

    $this->actingAs($user)
        ->post(route('recipes.featured.comments.store', 'crispy-chicken'), ['body' => $body])
        ->assertRedirect(route('recipes.featured', 'crispy-chicken'));

    $this->assertDatabaseHas('recipe_comments', [
        'user_id' => $user->id,
        'featured_recipe_slug' => 'crispy-chicken',
        'body' => $body,
    ]);

    $this->actingAs($user)
        ->get(route('recipes.featured', 'crispy-chicken'))
        ->assertSee('Recipe Fan')
        ->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt; Delicious and easy!', false)
        ->assertDontSee($body, false);

    $this->get(route('recipes.featured', 'fluffy-pancakes'))
        ->assertDontSee('Delicious and easy!');
});

it('stores and displays a comment on a saved recipe', function () {
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
        'video_path' => null,
    ]);

    $this->actingAs($user)
        ->post(route('recipes.comments.store', $recipe), ['body' => 'I made this for dinner.'])
        ->assertRedirect(route('recipes.show', $recipe));

    $this->assertDatabaseHas('recipe_comments', [
        'user_id' => $user->id,
        'recipe_id' => $recipe->id,
        'body' => 'I made this for dinner.',
    ]);

    $this->get(route('recipes.show', $recipe))
        ->assertSee('Comments')
        ->assertSee('I made this for dinner.');
});

it('requires a signed-in user and a valid comment', function () {
    $user = User::factory()->create();

    $this->post(route('recipes.featured.comments.store', 'crispy-chicken'), ['body' => 'A comment'])
        ->assertRedirect(route('login'));

    $this->actingAs($user)
        ->from(route('recipes.featured', 'crispy-chicken'))
        ->post(route('recipes.featured.comments.store', 'crispy-chicken'), ['body' => ''])
        ->assertRedirect(route('recipes.featured', 'crispy-chicken'))
        ->assertSessionHasErrors(['body']);

    $this->assertDatabaseCount('recipe_comments', 0);
});

it('does not accept comments on an unknown featured recipe', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('recipes.featured.comments.store', 'not-a-real-recipe'), ['body' => 'A comment'])
        ->assertNotFound();

    $this->assertDatabaseCount('recipe_comments', 0);
});
