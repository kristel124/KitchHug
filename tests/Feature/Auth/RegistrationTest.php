<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk()
        ->assertSee('Back to home')
        ->assertSee(route('home'));
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'New-pass1!',
        'password_confirmation' => 'New-pass1!',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('login', absolute: false))
        ->assertSessionHas('status', 'Account created successfully. Please log in.');

    $this->assertGuest();

    $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'New-pass1!',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('user_homepage', absolute: false));

    $this->assertAuthenticated();
});

test('users cannot register with their email address as their password', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'test@example.com',
        'password_confirmation' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors('password');

    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});

test('users cannot register with an email address that is already in use', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'Strong-pass1!',
        'password_confirmation' => 'Strong-pass1!',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertDatabaseCount('users', 1);
});

test('registration passwords must be 8 to 12 characters and contain a special character', function (string $password, bool $valid) {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    if ($valid) {
        $response->assertSessionHasNoErrors();

        return;
    }

    $response->assertSessionHasErrors('password');
})->with([
    'minimum length with special character' => ['Abcdef1!', true],
    'maximum length with special character' => ['Abcdefghij1!', true],
    'too short' => ['Abc1!', false],
    'too long' => ['Abcdefghijk1!', false],
    'missing special character' => ['Abcdefg123', false],
]);
