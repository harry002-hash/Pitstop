<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    public const STATUS_BELUM_SERVIS = 'belum_servis';

    public const STATUS_SEDANG_SERVIS = 'sedang_servis';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'user_id',
        'vehicle_name',
        'plate_number',
        'plate_password',
        'status',
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
}
