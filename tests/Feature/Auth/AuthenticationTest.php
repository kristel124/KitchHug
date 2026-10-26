<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Passkeys\Passkey;
use Laravel\Passkeys\Passkeys;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk()
        ->assertSee('Back to home')
        ->assertSee(route('home'));
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('user_homepage', absolute: false));

    $this->assertAuthenticated();
});

test('users cannot authenticate when another account uses the same email ignoring case', function () {
    $user = User::factory()->create(['email' => 'same@example.com']);
    User::factory()->create(['email' => 'SAME@example.com']);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users cannot authenticate with a passkey when another account uses the same email', function () {
    $user = User::factory()->create(['email' => 'same@example.com']);
    User::factory()->create(['email' => 'SAME@example.com']);
    $passkey = new Passkey;
    $passkey->setRelation('user', $user);

    expect(Passkeys::allowsLogin(Request::create('/passkeys/login'), $passkey))->toBeFalse();
});

test('authenticated users visiting the welcome page are redirected to their homepage', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertRedirect(route('user_homepage'));
});

test('login with the welcome page as the intended destination reaches the user homepage', function () {
    $user = User::factory()->create();

    $this->withSession(['url.intended' => route('home', absolute: false)])
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('home', absolute: false));

    $this->get(route('home'))
        ->assertRedirect(route('user_homepage'));

    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
