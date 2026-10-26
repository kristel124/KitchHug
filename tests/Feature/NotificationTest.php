<?php

use App\Models\Recipe;
use App\Models\User;
use App\Notifications\RecipeActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('followers are notified when a user publishes a recipe', function () {
    Storage::fake('public');
    $author = User::factory()->create();
    $follower = User::factory()->create();
    $author->followers()->attach($follower);

    $this->actingAs($author)
        ->post(route('recipes.store'), [
            'title' => 'Fresh Tomato Pasta',
            'category' => 'Dinner',
            'method' => 'Boiling',
            'recipe_type' => 'Pasta Recipes',
            'description' => 'A fresh pasta recipe.',
            'ingredients' => 'Pasta and tomatoes',
            'instructions' => 'Boil and mix.',
            'prep_time_minutes' => 25,
            'photo' => UploadedFile::fake()->createWithContent(
                'pasta.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/f3sAAAAASUVORK5CYII=')
            ),
        ])
        ->assertRedirect(route('profile.show'));

    $recipe = Recipe::query()->where('title', 'Fresh Tomato Pasta')->firstOrFail();

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $follower->id,
        'type' => 'App\\Notifications\\RecipeActivityNotification',
    ]);

    $notification = $follower->notifications()->firstOrFail();
    expect($notification->data['activity'])->toBe('new_recipe')
        ->and($notification->data['recipe_id'])->toBe($recipe->id);

    $this->actingAs($follower)
        ->get(route('user_homepage'))
        ->assertOk()
        ->assertSee('1 unread')
        ->assertSee('shared a new recipe: Fresh Tomato Pasta');
});

test('recipe owners are notified about comments from other users only', function () {
    $owner = User::factory()->create();
    $commenter = User::factory()->create();
    $recipe = Recipe::create([
        'user_id' => $owner->id,
        'title' => 'Garden Soup',
        'category' => 'Lunch',
        'description' => 'A fresh soup.',
        'ingredients' => 'Vegetables',
        'instructions' => 'Simmer.',
        'prep_time_minutes' => 30,
        'image_path' => 'recipe-images/garden-soup.jpg',
    ]);

    $this->actingAs($commenter)
        ->post(route('recipes.comments.store', $recipe), ['body' => 'Looks delicious!'])
        ->assertRedirect(route('recipes.show', $recipe));

    $notification = $owner->notifications()->firstOrFail();
    expect($notification->data['activity'])->toBe('recipe_comment')
        ->and($notification->data['url'])->toContain('#comments');

    $this->actingAs($owner)
        ->post(route('recipes.comments.store', $recipe), ['body' => 'Thank you!'])
        ->assertRedirect(route('recipes.show', $recipe));

    expect($owner->notifications()->count())->toBe(1);
});

test('following a cook creates a notification that opens and marks as read', function () {
    $follower = User::factory()->create();
    $author = User::factory()->create();

    $this->actingAs($follower)
        ->post(route('users.follow', $author))
        ->assertRedirect();

    $notification = $author->notifications()->firstOrFail();
    expect($notification->data['activity'])->toBe('new_follower');

    $this->actingAs($author)
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('started following you')
        ->assertSee('1 unread');

    $this->post(route('notifications.open', $notification->id))
        ->assertRedirect(route('users.show', $follower));

    expect($notification->fresh()->read_at)->not->toBeNull();

    $this->get(route('user_homepage'))
        ->assertOk()
        ->assertDontSee('99+');
});

test('users can mark all notifications as read', function () {
    $user = User::factory()->create();
    $actor = User::factory()->create();
    $user->notify(new RecipeActivityNotification('new_follower', $actor));

    $this->actingAs($user)
        ->post(route('notifications.read-all'))
        ->assertRedirect(route('notifications.index'))
        ->assertSessionHas('status', 'All notifications marked as read.');

    expect($user->unreadNotifications()->count())->toBe(0);
});
