<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('profile shows only the signed in user recipes and offers account actions', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Recipe::create([
        'user_id' => $user->id,
        'title' => 'My Tomato Soup',
        'category' => 'Lunch',
        'description' => 'A bright and easy soup.',
        'ingredients' => 'Tomatoes',
        'instructions' => 'Simmer and blend.',
        'prep_time_minutes' => 25,
        'image_path' => 'recipe-images/tomato-soup.jpg',
        'video_path' => 'recipe-videos/tomato-soup.mp4',
    ]);

    Recipe::create([
        'user_id' => $otherUser->id,
        'title' => 'Someone Else Recipe',
        'category' => 'Dinner',
        'description' => 'Not mine.',
        'ingredients' => 'Rice',
        'instructions' => 'Cook it.',
        'prep_time_minutes' => 30,
        'image_path' => 'recipe-images/other.jpg',
        'video_path' => null,
    ]);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee('My Tomato Soup')
        ->assertSee('Edit profile')
        ->assertSee('Share a recipe')
        ->assertSee('<video controls', false)
        ->assertDontSee('Someone Else Recipe');
});

test('a user can publish and update their recipe', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('recipes.store'), [
            'title' => 'Garden Pasta',
            'category' => 'Dinner',
            'description' => 'Fresh herbs and vegetables.',
            'ingredients' => "Pasta\nTomatoes",
            'instructions' => 'Boil pasta and mix with vegetables.',
            'prep_time_minutes' => 35,
            'video' => UploadedFile::fake()->create('garden-pasta.mp4', 100, 'video/mp4'),
            'photo' => UploadedFile::fake()->create('garden-pasta.jpg', 100, 'image/jpeg'),
        ])
        ->assertRedirect(route('profile.show'));

    $recipe = Recipe::query()->where('title', 'Garden Pasta')->firstOrFail();

    $this->assertSame($user->id, $recipe->user_id);
    Storage::disk('public')->assertExists($recipe->image_path);
    Storage::disk('public')->assertExists($recipe->video_path);

    $this->actingAs($user)
        ->put(route('recipes.update', $recipe), [
            'title' => 'Herby Garden Pasta',
            'category' => 'Dinner',
            'description' => 'Fresh herbs and vegetables.',
            'ingredients' => "Pasta\nTomatoes",
            'instructions' => 'Boil pasta and mix with vegetables.',
            'prep_time_minutes' => 35,
        ])
        ->assertRedirect(route('profile.show'));

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Herby Garden Pasta']);

    $videoPath = $recipe->video_path;
    $this->actingAs($user)
        ->delete(route('recipes.destroy', $recipe))
        ->assertRedirect(route('profile.show'));

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    Storage::disk('public')->assertMissing($videoPath);
});

test('users cannot edit or delete another users recipe', function () {
    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    $recipe = Recipe::create([
        'user_id' => $owner->id,
        'title' => 'Owner Recipe',
        'category' => 'Lunch',
        'description' => 'A recipe.',
        'ingredients' => 'Beans',
        'instructions' => 'Cook beans.',
        'prep_time_minutes' => 20,
        'image_path' => 'recipe-images/owner.jpg',
        'video_path' => null,
    ]);

    $this->actingAs($visitor)
        ->get(route('recipes.edit', $recipe))
        ->assertNotFound();

    $this->actingAs($visitor)
        ->delete(route('recipes.destroy', $recipe))
        ->assertNotFound();

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id]);
});

test('a user can view their recipe on its own information page', function () {
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
        ->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee('Garden Pasta')
        ->assertSee('Pasta and tomatoes')
        ->assertSee('Boil pasta and mix with vegetables.');
});

test('recipe creation validates required fields', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('recipes.create'))
        ->assertOk()
        ->assertSee('Post a new recipe')
        ->assertSee('Publish recipe')
        ->assertSee('name="photo"', false)
        ->assertSee('name="video"', false)
        ->assertSee('accept="video/mp4,video/quicktime,video/webm"', false)
        ->assertSee('required', false);

    $this->from(route('recipes.create'))
        ->post(route('recipes.store'), ['title' => ''])
        ->assertRedirect(route('recipes.create'))
        ->assertSessionHasErrors(['title', 'category', 'description', 'ingredients', 'instructions', 'prep_time_minutes', 'photo']);
});
