<?php

use App\Models\Message;
use App\Models\Vehicle;

test('customer dashboard shows the real vehicle from database', function () {
    $user = customer();

    Vehicle::factory()->claimedBy($user)->create([
        'plate_number' => 'KB 7777 XY',
        'vehicle_name' => 'Vario 160',
        'vehicle_type' => 'Motor',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs($user)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->assertSee('KB 7777 XY')
        ->assertSee('Vario 160')
        ->assertSee('Sedang Servis')
        ->assertDontSee('KB 8123 XG');
});

test('staff edit page is prefilled with the real vehicle', function () {
    $vehicle = Vehicle::factory()->create([
        'plate_number' => 'KB 3333 QQ',
        'vehicle_name' => 'Scoopy',
    ]);

    $this->actingAs(owner())
        ->get(route('vehicles.edit', $vehicle))
        ->assertOk()
        ->assertSee('KB 3333 QQ')
        ->assertSee('Scoopy')
        ->assertSee('Belum Servis')
        ->assertDontSee('KB 8123 XG');
});

test('customer can update plate and name of their own vehicle', function () {
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'plate_number' => 'KB 1212 AB',
        'vehicle_name' => 'Beat',
    ]);

    $this->actingAs($user)->put(route('vehicles.update', $vehicle), [
        'plate_number' => 'KB 3434 CD',
        'vehicle_name' => 'Beat Street',
    ])->assertRedirect(route('customer.dashboard'));

    $vehicle->refresh();

    expect($vehicle->plate_number)->toBe('KB 3434 CD')
        ->and($vehicle->vehicle_name)->toBe('Beat Street');
});

test('customer cannot change the service status', function () {
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs($user)->put(route('vehicles.update', $vehicle), [
        'plate_number' => $vehicle->plate_number,
        'vehicle_name' => $vehicle->vehicle_name,
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('customer.dashboard'));

    expect($vehicle->refresh()->status)->toBe(Vehicle::STATUS_SEDANG_SERVIS);
});

test('customer cannot edit another customer vehicle', function () {
    $vehicle = Vehicle::factory()->claimedBy(customer())->create();

    $this->actingAs(customer())
        ->get(route('vehicles.edit', $vehicle))
        ->assertForbidden();

    $this->actingAs(customer())
        ->put(route('vehicles.update', $vehicle), [
            'plate_number' => 'KB 0000 XX',
            'vehicle_name' => 'Curian',
        ])
        ->assertForbidden();
});

test('admin can update any vehicle including status', function () {
    $vehicle = Vehicle::factory()->create([
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs(admin())->put(route('vehicles.update', $vehicle), [
        'plate_number' => $vehicle->plate_number,
        'vehicle_name' => $vehicle->vehicle_name,
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect($vehicle->refresh()->status)->toBe(Vehicle::STATUS_SEDANG_SERVIS);
});

test('guests cannot open the vehicle edit page', function () {
    $vehicle = Vehicle::factory()->create();

    $this->get(route('vehicles.edit', $vehicle))->assertRedirect(route('login'));
});

test('changing status to selesai sends a chat message to the owner', function () {
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'plate_number' => 'KB 9999 QQ',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs(owner())->put(route('vehicles.update', $vehicle), [
        'plate_number' => 'KB 9999 QQ',
        'vehicle_name' => $vehicle->vehicle_name,
        'status' => Vehicle::STATUS_SELESAI,
    ]);

    $message = Message::where('receiver_id', $user->id)->latest('id')->first();

    expect($message)->not->toBeNull()
        ->and($message->message)->toContain('SELESAI')
        ->and($message->message)->toContain('KB 9999 QQ');
});

test('changing status to non-selesai sends no message', function () {
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs(owner())->put(route('vehicles.update', $vehicle), [
        'plate_number' => $vehicle->plate_number,
        'vehicle_name' => $vehicle->vehicle_name,
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    expect(Message::where('receiver_id', $user->id)->count())->toBe(0);
});
