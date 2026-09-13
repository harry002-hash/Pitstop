<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can open owner dashboard and vehicle list', function () {
    $admin = User::factory()->create(['username' => 'admin', 'role' => 'admin']);

    $this->actingAs($admin)->get(route('owner.dashboard'))->assertOk();

    $this->actingAs($admin)->get(route('owner.vehicles.index'))->assertOk();

    $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('owner.dashboard'));
});

test('owner can open owner dashboard', function () {
    $owner = User::factory()->create(['role' => 'owner']);

    $this->actingAs($owner)->get(route('owner.dashboard'))->assertOk();
});

test('guests are redirected to login on protected pages', function () {
    $this->get(route('owner.dashboard'))->assertRedirect(route('login'));
    $this->get(route('owner.vehicles.index'))->assertRedirect(route('login'));
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});
