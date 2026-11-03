<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    /**
     * Staff boleh mengubah semua motor; customer hanya motor miliknya.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isStaff() || $vehicle->user_id === $user->id;
    }
}
