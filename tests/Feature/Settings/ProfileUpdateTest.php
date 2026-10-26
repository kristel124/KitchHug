<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('data-test="delete-user-button"', false)
        ->assertSee('Delete account');
});

test('tapping delete account opens a responsive password confirmation dialog', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.delete-user-modal')
        ->assertSet('showConfirmation', false)
        ->call('openConfirmation')
        ->assertSet('showConfirmation', true)
        ->assertSee('role="alertdialog"', false)
        ->assertSee('id="delete_account_password"', false)
        ->assertSee('data-test="confirm-delete-user-button"', false)
        ->assertSee('min-h-11 w-full', false)
        ->assertSee('sm:w-auto', false)
        ->call('cancelDeletion')
        ->assertSet('showConfirmation', false)
        ->assertSet('password', '');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $originalName = $user->name;
    $originalEmail = $user->email;

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('profile_current_password', 'password')
        ->call('updateProfileInformation')
        ->assertSet('confirmingProfileUpdate', true);

    $response->assertHasNoErrors();

    expect($user->refresh()->name)->toBe($originalName)
        ->and($user->email)->toBe($originalEmail);

    $response->call('confirmProfileInformationUpdate')
        ->assertHasNoErrors()
        ->assertSet('confirmingProfileUpdate', false)
        ->assertSet('profile_current_password', '');

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->set('profile_current_password', 'password')
        ->call('updateProfileInformation')
        ->call('confirmProfileInformationUpdate');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('personal information requires the correct current password', function () {
    $user = User::factory()->create(['name' => 'Original Name']);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('name', 'Changed Name')
        ->set('email', $user->email)
        ->set('profile_current_password', 'wrong-password')
        ->call('updateProfileInformation')
        ->assertHasErrors(['profile_current_password'])
        ->assertSet('confirmingProfileUpdate', false);

    expect($user->refresh()->name)->toBe('Original Name');
});

test('personal information remains unchanged until explicitly confirmed', function () {
    $user = User::factory()->create(['name' => 'Original Name']);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('name', 'Pending Name')
        ->set('email', 'pending@example.com')
        ->set('profile_current_password', 'password')
        ->call('updateProfileInformation')
        ->assertSee('Confirm profile changes')
        ->assertSee('Pending Name')
        ->call('cancelProfileInformationUpdate')
        ->assertSet('confirmingProfileUpdate', false)
        ->assertSet('profile_current_password', '');

    expect($user->refresh()->name)->toBe('Original Name')
        ->and($user->email)->not->toBe('pending@example.com');
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->call('openConfirmation')
        ->set('confirm_deletion', true)
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('profile settings rejects a password that matches the email address', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('current_password', 'password')
        ->set('password', $user->email)
        ->set('password_confirmation', $user->email)
        ->call('updatePassword')
        ->assertHasErrors(['password']);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->call('openConfirmation')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

test('account deletion requires explicit confirmation as well as the password', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.delete-user-modal')
        ->call('openConfirmation')
        ->set('password', 'password')
        ->call('deleteUser')
        ->assertHasErrors(['confirm_deletion']);

    expect($user->fresh())->not->toBeNull();
});

test('account deletion confirmation still requires the correct password', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.delete-user-modal')
        ->call('openConfirmation')
        ->set('confirm_deletion', true)
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
