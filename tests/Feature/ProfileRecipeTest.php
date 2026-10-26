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
        ->assertSee('View public profile')
        ->assertSee(route('users.show', $user))
        ->assertSee('Share a recipe')
        ->assertSee('data-recipe-video-player', false)
        ->assertSee('<video controls', false)
        ->assertDontSee('Someone Else Recipe');
});

test('profile recipes can be filtered and sorted', function () {
    $user = User::factory()->create();
    $lunchRecipe = Recipe::create([
        'user_id' => $user->id,
        'title' => 'Apple Salad',
        'category' => 'Lunch',
        'method' => 'Boiling',
        'recipe_type' => 'Side Dish',
        'description' => 'A bright salad.',
        'ingredients' => 'Apples',
        'instructions' => 'Mix together.',
        'prep_time_minutes' => 15,
        'image_path' => 'recipe-images/apple-salad.jpg',
    ]);
    Recipe::create([
        'user_id' => $user->id,
        'title' => 'Dinner Soup',
        'category' => 'Dinner',
        'method' => 'Boiling',
        'recipe_type' => 'Chicken Recipes',
        'description' => 'A warm soup.',
        'ingredients' => 'Chicken',
        'instructions' => 'Simmer.',
        'prep_time_minutes' => 40,
        'image_path' => 'recipe-images/dinner-soup.jpg',
    ]);

    $this->actingAs($user)
        ->get(route('profile.show', ['category' => 'Lunch', 'method' => 'Boiling', 'recipe_type' => 'Side Dish', 'sort' => 'title']))
        ->assertOk()
        ->assertSee('Apple Salad')
        ->assertDontSee('Dinner Soup')
        ->assertSee('All courses')
        ->assertSee('All methods')
        ->assertSee('All recipe types')
        ->assertSee('data-profile-recipe-view="grid"', false)
        ->assertSee('data-profile-recipe-view="list"', false)
        ->assertSee(route('recipes.show', $lunchRecipe));
});

test('profile recipe sort options return recipes in the selected order', function () {
    $user = User::factory()->create();
    $older = Recipe::create([
        'user_id' => $user->id,
        'title' => 'Zulu Soup',
        'category' => 'Dinner',
        'description' => 'Older recipe.',
        'ingredients' => 'Soup',
        'instructions' => 'Cook.',
        'prep_time_minutes' => 30,
        'image_path' => 'recipe-images/zulu-soup.jpg',
        'created_at' => now()->subDay(),
        'updated_at' => now()->subDay(),
    ]);
    $newer = Recipe::create([
        'user_id' => $user->id,
        'title' => 'Alpha Pasta',
        'category' => 'Lunch',
        'description' => 'Newer recipe.',
        'ingredients' => 'Pasta',
        'instructions' => 'Boil.',
        'prep_time_minutes' => 20,
        'image_path' => 'recipe-images/alpha-pasta.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('profile.show', ['sort' => 'newest']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Pasta', 'Zulu Soup']);

    $this->get(route('profile.show', ['sort' => 'oldest']))
        ->assertOk()
        ->assertSeeInOrder(['Zulu Soup', 'Alpha Pasta']);

    $this->get(route('profile.show', ['sort' => 'title']))
        ->assertOk()
        ->assertSeeInOrder(['Alpha Pasta', 'Zulu Soup'])
        ->assertSee('data-profile-sort-select', false);
});

test('a user can publish and update their recipe', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('recipes.store'), [
            'title' => 'Garden Pasta',
            'category' => 'Dinner',
            'method' => 'Boiling',
            'recipe_type' => 'Pasta Recipes',
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
    $this->assertSame('Boiling', $recipe->method);
    $this->assertSame('Pasta Recipes', $recipe->recipe_type);
    Storage::disk('public')->assertExists($recipe->image_path);
    Storage::disk('public')->assertExists($recipe->video_path);

    $this->actingAs($user)
        ->put(route('recipes.update', $recipe), [
            'title' => 'Herby Garden Pasta',
            'category' => 'Dinner',
            'method' => 'Frying',
            'recipe_type' => 'Pasta Recipes',
            'description' => 'Fresh herbs and vegetables.',
            'ingredients' => "Pasta\nTomatoes",
            'instructions' => 'Boil pasta and mix with vegetables.',
            'prep_time_minutes' => 35,
        ])
        ->assertRedirect(route('profile.show'));

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'title' => 'Herby Garden Pasta',
        'method' => 'Frying',
        'recipe_type' => 'Pasta Recipes',
    ]);

    $videoPath = $recipe->video_path;
    $this->actingAs($user)
        ->delete(route('recipes.destroy', $recipe))
        ->assertRedirect(route('profile.show'));

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    Storage::disk('public')->assertMissing($videoPath);
});

test('a user can replace their recipe photo while editing', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $oldPhotoPath = 'recipe-images/old-photo.png';
    Storage::disk('public')->put($oldPhotoPath, 'old photo');
    $recipe = Recipe::create([
        'user_id' => $user->id,
        'title' => 'Garden Pasta',
        'category' => 'Dinner',
        'method' => 'Boiling',
        'recipe_type' => 'Pasta Recipes',
        'description' => 'Fresh herbs and vegetables.',
        'ingredients' => "Pasta\nTomatoes",
        'instructions' => 'Boil pasta and mix with vegetables.',
        'prep_time_minutes' => 35,
        'image_path' => $oldPhotoPath,
        'video_path' => null,
    ]);

    $this->actingAs($user)
        ->get(route('recipes.edit', $recipe))
        ->assertOk()
        ->assertSee('data-photo-preview', false)
        ->assertSee('Your new photo will preview here.');

    $this->actingAs($user)
        ->post(route('recipes.update', $recipe), [
            '_method' => 'PUT',
            'title' => $recipe->title,
            'category' => $recipe->category,
            'method' => $recipe->method,
            'recipe_type' => $recipe->recipe_type,
            'description' => $recipe->description,
            'ingredients' => $recipe->ingredients,
            'instructions' => $recipe->instructions,
            'prep_time_minutes' => $recipe->prep_time_minutes,
            'photo' => UploadedFile::fake()->createWithContent(
                'new-photo.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/f3sAAAAASUVORK5CYII=')
            ),
        ])
        ->assertRedirect(route('profile.show'));

    $recipe->refresh();

    expect($recipe->image_path)->not->toBe($oldPhotoPath);
    Storage::disk('public')->assertExists($recipe->image_path);
    Storage::disk('public')->assertMissing($oldPhotoPath);
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
        'method' => 'Boiling',
        'recipe_type' => 'Pasta Recipes',
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
        ->assertSee('Pasta Recipes')
        ->assertSee('Boiling')
        ->assertSee('Pasta and tomatoes')
        ->assertSee('Boil pasta and mix with vegetables.');
});

test('recipe creation validates required fields', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('recipes.create'))
        ->assertOk()
        ->assertSee('Post a new recipe')
        ->assertSee('Publish recipe')
        ->assertSee('name="method"', false)
        ->assertSee('name="recipe_type"', false)
        ->assertSee('Choose a course')
        ->assertSee('Choose a method')
        ->assertSee('Choose a recipe type')
        ->assertSee('Images and videos')
        ->assertSee('>Images</h3>', false)
        ->assertSee('>Videos</h3>', false)
        ->assertSee('Add one image and, if you like, one video.')
        ->assertSee('name="photo"', false)
        ->assertSee('name="video"', false)
        ->assertSee('accept="video/mp4,video/quicktime,video/webm"', false)
        ->assertSee('required', false);

    $this->from(route('recipes.create'))
        ->post(route('recipes.store'), ['title' => ''])
        ->assertRedirect(route('recipes.create'))
        ->assertSessionHasErrors(['title', 'category', 'method', 'recipe_type', 'description', 'ingredients', 'instructions', 'prep_time_minutes', 'photo']);
});
