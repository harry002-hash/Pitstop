<?php

use App\Events\StaffAlert;
use App\Models\Message;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

function owner(): User
{
    return User::factory()->create(['role' => 'owner']);
}

function customer(): User
{
    return User::factory()->create(['role' => 'customer']);
}

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

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Vario',
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs($user)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
    ])->assertRedirect(route('customer.dashboard'));

    expect($vehicle->refresh()->user_id)->toBe($user->id);
});

test('claim fails with wrong plate password', function () {
    $user = customer();

    Vehicle::create([
        'vehicle_name' => 'Vario',
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs($user)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 9999 ZZ',
        'plate_password' => 'salah',
    ])->assertSessionHasErrors('plate_number');

    $this->get(route('customer.dashboard'))->assertRedirect(route('vehicle.claim'));
});

test('customer can update own vehicle plate and name', function () {
    $user = customer();

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 2222 BB',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->get(route('customer.vehicle.edit'))->assertOk();

    $this->actingAs($user)->put(route('customer.vehicle.update'), [
        'vehicle_name' => 'Beat Deluxe',
        'plate_number' => 'KB 3333 CC',
    ])->assertRedirect(route('customer.dashboard'));

    expect($vehicle->refresh())
        ->plate_number->toBe('KB 3333 CC')
        ->vehicle_name->toBe('Beat Deluxe')
        ->status->toBe(Vehicle::STATUS_BELUM_SERVIS); // status tidak ikut berubah
});

test('guest cannot open customer vehicle edit', function () {
    $this->get(route('customer.vehicle.edit'))->assertRedirect(route('login'));
});

test('admin can fetch notification history', function () {
    $staff = owner();
    $user = customer();

    Message::create(['sender_id' => $user->id, 'receiver_id' => $staff->id, 'message' => 'halo admin']);

    $this->actingAs($staff)->getJson(route('owner.notifications'))
        ->assertOk()
        ->assertJsonPath('notifications.0.type', 'chat')
        ->assertJsonPath('notifications.0.title', 'Chat baru dari '.$user->username);
});

test('customer cannot fetch notification history', function () {
    $this->actingAs(customer())->getJson(route('owner.notifications'))->assertForbidden();
});

test('customer chat notifies staff, staff reply does not', function () {
    Event::fake();

    $staff = owner();
    $user = customer();

    $this->actingAs($user)->postJson(route('chat.send'), ['message' => 'butuh bantuan'])
        ->assertOk();

    Event::assertDispatched(StaffAlert::class, fn (StaffAlert $e) => $e->type === 'chat' && $e->customerId === $user->id);

    Event::fake();

    $this->actingAs($staff)->postJson(route('chat.send'), ['message' => 'siap', 'to_user_id' => $user->id])
        ->assertOk();

    Event::assertNotDispatched(StaffAlert::class);
});

test('vehicle claim notifies staff', function () {
    Event::fake();

    owner();
    $user = customer();

    Vehicle::create([
        'vehicle_name' => 'Vario',
        'plate_number' => 'KB 7777 ZZ',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs($user)->post(route('vehicle.claim.store'), [
        'plate_number' => 'KB 7777 ZZ',
        'plate_password' => 'kunci123',
    ])->assertRedirect(route('customer.dashboard'));

    Event::assertDispatched(StaffAlert::class, fn (StaffAlert $e) => $e->type === 'claim');
});

test('owner can update status and customer sees the new status', function () {
    $user = customer();

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1111 AA',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs(owner())->put(route('owner.vehicles.update', $vehicle), [
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1111 AA',
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect($vehicle->refresh()->status)->toBe(Vehicle::STATUS_SELESAI);

    $this->actingAs($user)->get(route('customer.status'))
        ->assertOk()
        ->assertJson(['status' => Vehicle::STATUS_SELESAI]);
});

test('customer sees own staff replies in notifications', function () {
    $staff = owner();
    $user = customer();
    $other = customer();

    Message::create(['sender_id' => $staff->id, 'receiver_id' => $user->id, 'message' => 'motor selesai']);
    Message::create(['sender_id' => $staff->id, 'receiver_id' => $other->id, 'message' => 'rahasia orang']);

    Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 4444 DD',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)->getJson(route('customer.notifications'))
        ->assertOk()
        ->assertJsonPath('notifications.0.title', 'Balasan dari '.$staff->username)
        ->assertJsonPath('vehicle.status', Vehicle::STATUS_SEDANG_SERVIS)
        ->assertJsonMissing(['body' => 'rahasia orang']);
});

test('guest cannot fetch customer notifications', function () {
    $this->getJson(route('customer.notifications'))->assertUnauthorized();
});

test('failed vehicle update returns to index with edit id', function () {
    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 5555 EE',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
        'user_id' => owner()->id,
    ]);

    $this->actingAs(owner())->put(route('owner.vehicles.update', $vehicle), [
        'vehicle_name' => '',
        'plate_number' => 'KB 5555 EE',
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('owner.vehicles.index'))
        ->assertSessionHasErrors('vehicle_name')
        ->assertSessionHas('edit_id', $vehicle->id);
});

test('staff can send reminder to vehicle owner', function () {
    $staff = owner();
    $user = customer();

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 6666 FF',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs($staff)->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertOk()
        ->assertJson(['success' => true, 'to' => $user->username]);

    $msg = Message::latest()->first();
    expect($msg->sender_id)->toBe($staff->id)
        ->and($msg->receiver_id)->toBe($user->id)
        ->and($msg->message)->toContain('Pengingat');
});

test('reminder fails for unclaimed vehicle', function () {
    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 7777 GG',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
    ]);

    $this->actingAs(owner())->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertStatus(422);
});

test('customer cannot send reminder', function () {
    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 8888 HH',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
        'user_id' => customer()->id,
    ]);

    $this->actingAs(customer())->postJson(route('owner.vehicles.remind', $vehicle))
        ->assertForbidden();
});

test('changing status to selesai notifies the owner', function () {
    $user = customer();

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 9999 QQ',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs(owner())->put(route('owner.vehicles.update', $vehicle), [
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 9999 QQ',
        'status' => Vehicle::STATUS_SELESAI,
    ])->assertRedirect(route('owner.vehicles.index'));

    $msg = Message::where('receiver_id', $user->id)->latest()->first();

    expect($msg)->not->toBeNull()
        ->and($msg->message)->toContain('SELESAI')
        ->and($msg->message)->toContain('KB 9999 QQ');
});

test('changing status to non-selesai sends no message', function () {
    $user = customer();

    $vehicle = Vehicle::create([
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1111 WW',
        'plate_password' => 'kunci123',
        'status' => Vehicle::STATUS_BELUM_SERVIS,
        'user_id' => $user->id,
    ]);

    $this->actingAs(owner())->put(route('owner.vehicles.update', $vehicle), [
        'vehicle_name' => 'Beat',
        'plate_number' => 'KB 1111 WW',
        'status' => Vehicle::STATUS_SEDANG_SERVIS,
    ])->assertRedirect(route('owner.vehicles.index'));

    expect(Message::where('receiver_id', $user->id)->count())->toBe(0);
});
