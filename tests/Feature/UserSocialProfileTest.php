<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

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
        ->assertDontSee('Change photo')
        ->assertDontSee('name="profile_photo"', false);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Edit your profile')
        ->assertSee('name="profile_photo"', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('Change password')
        ->assertSee('name="current_password"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="password_confirmation"', false);

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->assertSee('Change')
        ->assertSee('name="profile_photo"', false);

    $this->actingAs($user)->post(route('profile.photo.update'), [
        'profile_photo' => UploadedFile::fake()->createWithContent(
            'avatar.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/f3sAAAAASUVORK5CYII=')
        ),
    ])->assertRedirect(route('profile.edit'));

    $user->refresh();
    expect($user->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->profile_photo_path);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($user->profile_photo_path));
});

test('profile edit page can update the account password', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->assertSee('Change password')
        ->assertSee('Current password')
        ->assertSee('New password')
        ->assertSee('Confirm new password')
        ->set('current_password', 'password')
        ->set('password', 'new-password-123')
        ->set('password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(password_verify('new-password-123', $user->refresh()->password))->toBeTrue();
});

test('profile edit page requires the current password before changing it', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->set('current_password', 'incorrect-password')
        ->set('password', 'new-password-123')
        ->set('password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    expect(password_verify('password', $user->refresh()->password))->toBeTrue();
});
