<?php

use App\Models\Message;
use App\Models\Vehicle;

/**
 * Arahkan driver broadcast ke port yang tidak ada supaya broadcast gagal,
 * meniru kondisi "Reverb belum dijalankan".
 */
function breakRealtimeServer(): void
{
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.options.host' => '127.0.0.1',
        'broadcasting.connections.reverb.options.port' => 1,
        'broadcasting.connections.reverb.options.scheme' => 'http',
        'broadcasting.connections.reverb.options.useTLS' => false,
        'broadcasting.connections.reverb.options.timeout' => 1,
    ]);
}

test('chat message is saved even when the realtime server is down', function () {
    breakRealtimeServer();

    $sender = customer();
    $receiver = owner();

    $this->actingAs($sender)
        ->postJson(route('chat.send'), [
            'receiver_id' => $receiver->id,
            'message' => 'Tetap masuk walau Reverb mati',
        ])
        ->assertOk();

    expect(Message::latest('id')->first()->message)->toBe('Tetap masuk walau Reverb mati');
});

test('status update is saved even when the realtime server is down', function () {
    breakRealtimeServer();

    $user = customer();
    $vehicle = Vehicle::factory()->claimedBy($user)->create([
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ]);

    $this->actingAs(owner())->put(route('vehicles.update', $vehicle), [
        'plate_number' => $vehicle->plate_number,
        'vehicle_name' => $vehicle->vehicle_name,
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect($vehicle->refresh()->status)->toBe(Vehicle::STATUS_SELESAI);
});
