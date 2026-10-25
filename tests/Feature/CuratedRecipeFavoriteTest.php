<?php

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
