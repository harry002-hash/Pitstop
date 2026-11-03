<?php

namespace App\Models;

use App\Events\MessageSent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Customer hanya boleh melihat pesan yang melibatkan dirinya;
     * staff melihat semua thread (inbox customer service).
     *
     * @param  Builder<Message>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if ($user->isStaff()) {
            return;
        }

        $query->where(function (Builder $query) use ($user): void {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        });
    }

    /**
     * Simpan pesan lalu broadcast ke penerimanya.
     */
    public static function deliver(User $sender, User $receiver, string $body): self
    {
        $message = self::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $body,
        ]);

        $message->setRelation('sender', $sender);

        // Pesan tetap tersimpan walau server Reverb sedang mati; customer
        // masih bisa melihatnya lewat halaman chat.
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (Throwable $exception) {
            report($exception);
        }

        return $message;
    }
}
