<?php

namespace App\Models;

use App\Events\VehicleStatusUpdated;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory;

    public const STATUS_BELUM_SERVIS = 'belum_servis';

    public const STATUS_SEDANG_SERVIS = 'sedang_servis';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_name',
        'plate_number',
        'plate_password',
        'status',
    ];

    /**
     * Hash password klaim tidak boleh ikut terserialisasi (JSON, event, dsb).
     *
     * @var list<string>
     */
    protected $hidden = [
        'plate_password',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'plate_password' => 'hashed',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_BELUM_SERVIS => 'Belum Servis',
            self::STATUS_SEDANG_SERVIS => 'Sedang Servis',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function isClaimed(): bool
    {
        return $this->user_id !== null;
    }

    /**
     * Payload status yang dipakai bersama oleh endpoint polling dan event real-time.
     *
     * @return array<string, mixed>
     */
    public function statusPayload(): array
    {
        return [
            'id' => $this->id,
            'plate_number' => $this->plate_number,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
        ];
    }

    /**
     * Siarkan perubahan status ke dashboard customer; kegagalan Reverb tidak
     * boleh membatalkan perubahan data yang sudah tersimpan.
     */
    public function broadcastStatusUpdate(): void
    {
        try {
            broadcast(new VehicleStatusUpdated($this));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Kirim pesan chat ke customer pemilik motor. Null kalau motor belum diklaim.
     */
    public function notifyOwner(User $sender, string $body): ?Message
    {
        if (! $this->owner) {
            return null;
        }

        return Message::deliver($sender, $this->owner, $body);
    }
}
