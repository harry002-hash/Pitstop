<?php

use App\Models\User;
use App\Models\Vehicle;
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

test('admin can generate an account that a customer claims to reach the dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($admin)->post(route('owner.vehicles.store'), [
        'vehicle_name' => 'Vario 125',
        'plate_number' => 'KB 1234 AB',
        'plate_password' => 'rahasia1',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ])->assertRedirect(route('owner.vehicles.index'));

    $this->actingAs($customer)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 1234 AB',
        'plate_password' => 'rahasia1',
    ])->assertRedirect(route('customer.dashboard'));

    $this->actingAs($customer)->get(route('customer.dashboard'))->assertOk();
});
