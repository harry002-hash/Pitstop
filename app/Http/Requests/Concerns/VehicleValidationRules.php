<?php

namespace App\Http\Requests\Concerns;

use App\Models\Vehicle;
use Illuminate\Validation\Rule;

trait VehicleValidationRules
{
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function vehicleRules(?Vehicle $vehicle = null): array
    {
        return [
            'vehicle_name' => ['required', 'string', 'max:100'],
            'vehicle_type' => ['nullable', 'string', 'max:50'],
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicle),
            ],
            'plate_password' => [
                $vehicle ? 'nullable' : 'required',
                'string',
                'min:4',
                'max:50',
            ],
            'status' => ['required', Rule::in(array_keys(Vehicle::statuses()))],
        ];
    }
}
