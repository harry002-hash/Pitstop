<?php

use App\Models\Message;
use App\Models\Vehicle;

test('staff can send a reminder to the vehicle owner', function () {
    $staff = owner();
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'plate_number' => 'KB 6666 FF',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs($staff)
        ->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertOk()
        ->assertJson(['success' => true, 'to' => $user->username]);

    $message = Message::latest('id')->first();

    expect($message->sender_id)->toBe($staff->id)
        ->and($message->receiver_id)->toBe($user->id)
        ->and($message->message)->toContain('Pengingat')
        ->and($message->message)->toContain('KB 6666 FF')
        ->and($message->message)->toContain('Sedang Servis');
});

test('admin can also send a reminder', function () {
    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create();

    $this->actingAs(admin())
        ->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertOk();
});

test('reminder fails when the vehicle is not claimed', function () {
    $vehicle = Vehicle::factory()->create();

    $this->actingAs(owner())
        ->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertStatus(422);
});

test('customer cannot send a reminder', function () {
    $vehicle = Vehicle::factory()->claimedBy(customer())->create();

    $this->actingAs(customer())
        ->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertForbidden();
});
