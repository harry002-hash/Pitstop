<?php

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

test('customer only sees their own conversation', function () {
    $me = customer();
    $other = customer();
    $staff = owner();

    Message::create(['sender_id' => $staff->id, 'receiver_id' => $me->id, 'message' => 'Halo khusus saya']);
    Message::create(['sender_id' => $staff->id, 'receiver_id' => $other->id, 'message' => 'Pesan orang lain']);

    $this->actingAs($me)
        ->get(route('chat'))
        ->assertOk()
        ->assertSee('Halo khusus saya')
        ->assertDontSee('Pesan orang lain');
});

test('staff sees every conversation', function () {
    $sender = customer();
    Message::create(['sender_id' => $sender->id, 'receiver_id' => owner()->id, 'message' => 'Pesan customer']);

    $this->actingAs(admin())
        ->get(route('chat'))
        ->assertOk()
        ->assertSee('Pesan customer');
});

test('sending a message stores it and returns json', function () {
    $sender = customer();
    $receiver = owner();

    $this->actingAs($sender)
        ->postJson(route('chat.send'), [
            'receiver_id' => $receiver->id,
            'message' => 'Halo bengkel',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $message = Message::latest('id')->first();

    expect($message->sender_id)->toBe($sender->id)
        ->and($message->receiver_id)->toBe($receiver->id)
        ->and($message->message)->toBe('Halo bengkel');
});

test('customer cannot send a message to themselves', function () {
    $user = customer();

    $this->actingAs($user)
        ->postJson(route('chat.send'), [
            'receiver_id' => $user->id,
            'message' => 'Halo diri sendiri',
        ])
        ->assertStatus(422);
});

test('guests cannot open the chat', function () {
    $this->get(route('chat'))->assertRedirect(route('login'));
});

test('message events are broadcast privately to the receiver', function () {
    $sender = customer();
    $receiver = owner();

    $message = Message::create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'message' => 'Tes channel',
    ]);

    $event = new MessageSent($message);
    $channels = $event->broadcastOn();

    expect($event)->toBeInstanceOf(ShouldBroadcast::class)
        ->and($channels[0]->name)->toBe('private-App.Models.User.'.$receiver->id)
        ->and($event->broadcastAs())->toBe('message.sent')
        ->and($event->broadcastWith()['sender'])->toBe($sender->username);
});

test('private user channel only authorizes its owner', function () {
    // Driver broadcast di-resolve saat channel didaftarkan, jadi daftarkan ulang
    // routes/channels.php ke driver reverb (di test default-nya null).
    config(['broadcasting.default' => 'reverb']);
    require base_path('routes/channels.php');

    $me = customer();
    $other = customer();

    $this->actingAs($me)->post('/broadcasting/auth', [
        'socket_id' => '1234.5678',
        'channel_name' => 'private-App.Models.User.'.$me->id,
    ])->assertOk()->assertJsonStructure(['auth']);

    $this->actingAs($me)->post('/broadcasting/auth', [
        'socket_id' => '1234.5678',
        'channel_name' => 'private-App.Models.User.'.$other->id,
    ])->assertForbidden();
});
