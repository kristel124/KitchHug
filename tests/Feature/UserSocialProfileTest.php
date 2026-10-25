<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('user names link to public profiles with shared recipes and follow counts', function () {
    $author = User::factory()->create(['name' => 'Recipe Author']);
    $recipe = Recipe::create([
        'user_id' => $author->id,
        'title' => 'Herb Chicken',
        'category' => 'Dinner',
        'description' => 'Roasted chicken with herbs.',
        'ingredients' => 'Chicken and herbs',
        'instructions' => 'Roast until cooked.',
        'prep_time_minutes' => 50,
        'image_path' => 'recipe-images/chicken.jpg',
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('recipes.show', $recipe))
        ->assertOk()
        ->assertSee(route('users.show', $author))
        ->assertSee('Recipe Author');

    $this->get(route('users.show', $author))
        ->assertOk()
        ->assertSee('Recipe Author')
        ->assertSee('Herb Chicken')
        ->assertSee('Recipes shared')
        ->assertSee('Followers')
        ->assertSee('Following');
});

test('authenticated users can follow and unfollow another profile but not themselves', function () {
    $follower = User::factory()->create();
    $author = User::factory()->create();
    $this->actingAs($follower);

    $this->post(route('users.follow', $author))
        ->assertRedirect();
    expect($author->followers()->whereKey($follower->id)->exists())->toBeTrue();

    $this->get(route('users.show', $author))
        ->assertOk()
        ->assertSee('1')
        ->assertSee('Following');

    $this->post(route('users.follow', $author))
        ->assertRedirect();
    expect($author->followers()->whereKey($follower->id)->exists())->toBeFalse();

    $this->post(route('users.follow', $follower))->assertForbidden();
});

test('my profile shows follow counts and accepts a profile photo upload', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $follower = User::factory()->create();
    $user->followers()->attach($follower);
    $following = User::factory()->create();
    $user->following()->attach($following);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee('Followers')
        ->assertSee('Following')
        ->assertSee('Upload');

    $this->post(route('profile.photo.update'), [
        'profile_photo' => UploadedFile::fake()->createWithContent(
            'avatar.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/f3sAAAAASUVORK5CYII=')
        ),
    ])->assertRedirect(route('profile.show'));

    $user->refresh();
    expect($user->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->profile_photo_path);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($user->profile_photo_path));
});
