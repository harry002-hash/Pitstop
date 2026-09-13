<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $customerId;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message, ?int $customerId = null)
    {
        // Satu thread per customer: customerId = id customer-nya,
        // bukan id staff. Kalau tidak dikasih, fallback ke sender.
        $this->customerId = $customerId ?? (int) $message->sender_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support.'.$this->customerId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Payload yang diterima JS.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $sender = $this->message->relationLoaded('sender')
            ? $this->message->sender
            : $this->message->sender()->first(['id', 'username']);

        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender' => $sender?->username ?? 'User',
            'message' => $this->message->message,
            'image_url' => $this->message->image ? asset('storage/'.$this->message->image) : null,
            'customer_id' => $this->customerId,
        ];
    }
}
