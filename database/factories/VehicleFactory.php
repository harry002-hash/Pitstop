<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_name' => fake()->randomElement(['Beat', 'Vario 125', 'NMAX', 'Scoopy']),
            'vehicle_type' => 'Motor',
            'plate_number' => strtoupper(fake()->unique()->bothify('KB #### ??')),
            'plate_password' => 'kunci123',
            'status' => Vehicle::STATUS_BELUM_SERVIS,
        ];
    }

    public function claimedBy(User $user): static
    {
        return $this->state(fn (): array => ['user_id' => $user->id]);
    }

    public function status(string $status): static
    {
        return $this->state(fn (): array => ['status' => $status]);
    }
}
