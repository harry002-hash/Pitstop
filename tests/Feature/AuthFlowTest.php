<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('register creates a user in the database and logs them in', function () {
    $response = $this->post(route('register.store'), [
        'username' => 'budi',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', ['username' => 'budi']);
    $this->assertAuthenticatedAs(User::where('username', 'budi')->first());
});

test('register rejects a duplicate username', function () {
    User::factory()->create(['username' => 'budi']);

    $this->post(route('register.store'), [
        'username' => 'budi',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ])->assertSessionHasErrors('username');

    expect(User::where('username', 'budi')->count())->toBe(1);
});

test('login authenticates with correct credentials', function () {
    User::factory()->create(['username' => 'siti']);

    $this->post(route('login.attempt'), [
        'username' => 'siti',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs(User::where('username', 'siti')->first());
});

test('login rejects a wrong password', function () {
    User::factory()->create(['username' => 'siti']);

    $this->post(route('login.attempt'), [
        'username' => 'siti',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});
