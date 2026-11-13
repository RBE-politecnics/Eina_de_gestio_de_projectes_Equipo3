<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('signs up a user and authenticates them', function () {
    $response = $this->post(route('sign.up.store'), [
        'email' => 'new-user@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'new-user@example.com',
    ]);
    expect(Hash::check('password', User::query()->firstOrFail()->password))->toBeTrue();
});

it('requires matching signup passwords', function () {
    $response = $this->from(route('sign.up'))->post(route('sign.up.store'), [
        'email' => 'new-user@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertRedirect(route('sign.up'));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
