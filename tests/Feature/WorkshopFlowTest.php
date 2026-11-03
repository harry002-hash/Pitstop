<?php

use App\Models\Vehicle;

test('owner can record a new vehicle with plate password', function () {
    $this->actingAs(owner())->post(route('owner.vehicles.store'), [
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1234 AB',
        'plate_password' => 'rahasia1',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ])->assertRedirect(route('owner.vehicles.index'));

    $vehicle = Vehicle::where('plate_number', 'KB 1234 AB')->first();

    expect($vehicle)->not->toBeNull()
        ->and($vehicle->plate_password)->not->toBe('rahasia1'); // hashed
});

test('admin can also record a vehicle', function () {
    $this->actingAs(admin())->post(route('owner.vehicles.store'), [
        'vehicle_name' => 'NMAX',
        'plate_number' => 'KB 4321 BA',
        'plate_password' => 'rahasia1',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect(Vehicle::where('plate_number', 'KB 4321 BA')->exists())->toBeTrue();
});

test('customer cannot access owner area', function () {
    $this->actingAs(customer())
        ->get(route('owner.vehicles.index'))
        ->assertForbidden();

    $this->actingAs(customer())
        ->post(route('owner.vehicles.store'), [])
        ->assertForbidden();
});

test('customer without vehicle is redirected to claim page', function () {
    $this->actingAs(customer())
        ->get(route('dashboard'))
        ->assertRedirect(route('vehicle.claim'));

    $this->actingAs(customer())
        ->get(route('customer.dashboard'))
        ->assertRedirect(route('vehicle.claim'));
});

test('customer can claim vehicle with correct plate password', function () {
    $user = customer();

    $vehicle = Vehicle::factory()->create([
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
    ]);

    $this->actingAs($user)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
    ])->assertRedirect(route('customer.dashboard'));

    expect($vehicle->refresh()->user_id)->toBe($user->id);
});

test('claim fails with wrong plate password', function () {
    $user = customer();

    Vehicle::factory()->create([
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
    ]);

    $this->actingAs($user)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'salah',
    ])->assertSessionHasErrors('plate_number');

    $this->get(route('customer.dashboard'))->assertRedirect(route('vehicle.claim'));
});

test('owner can update status and customer sees the new status', function () {
    $user = customer();

    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'plate_number' => 'KB 1111 AA',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs(owner())->put(route('vehicles.update', $vehicle), [
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1111 AA',
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect($vehicle->refresh()->status)->toBe(Vehicle::STATUS_SELESAI);

    $this->actingAs($user)->get(route('customer.status'))
        ->assertOk()
        ->assertJson(['status' => Vehicle::STATUS_SELESAI]);
});
