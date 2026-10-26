<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admins are redirected to their dashboard after logging in', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->post(route('login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ])
        ->assertRedirect(route('user_homepage', absolute: false));

    $this
        ->get(route('user_homepage'))
        ->assertRedirect(route('admin.dashboard'));
});

test('admins can view the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Admin dashboard')
        ->assertSee('Registered users')
        ->assertSee('Recipes shared')
        ->assertSee('Recipe comments');
});

test('regular users cannot view the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('public registration cannot grant admin privileges', function () {
    $this->post(route('register.store'), [
        'name' => 'New User',
        'email' => 'new-user@example.com',
        'password' => 'Admin-pass1!',
        'password_confirmation' => 'Admin-pass1!',
        'is_admin' => true,
    ])->assertSessionHasNoErrors();

    expect(User::where('email', 'new-user@example.com')->firstOrFail()->isAdmin())->toBeFalse();
});

test('seeding promotes the admin account without creating duplicates or resetting its password', function () {
    $admin = User::factory()->create(['email' => 'admin@gmail.com']);
    $password = $admin->password;

    $this->seed();
    $this->seed();

    expect(User::where('email', 'admin@gmail.com')->count())->toBe(1)
        ->and($admin->fresh()->isAdmin())->toBeTrue()
        ->and($admin->fresh()->password)->toBe($password);
});

test('seeding creates the admin account in a fresh database', function () {
    $this->seed();

    $admin = User::where('email', 'admin@gmail.com')->firstOrFail();

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->email_verified_at)->not->toBeNull();
});
