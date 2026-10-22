<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\VehicleValidationRules;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    use VehicleValidationRules;

    /**
     * Staff boleh mengubah semua motor, customer hanya motornya sendiri.
     */
    public function authorize(): bool
    {
        $vehicle = $this->route('vehicle');

        return $vehicle instanceof Vehicle
            && ($this->user()?->can('update', $vehicle) ?? false);
    }

    /**
     * Customer tidak boleh mengubah status / jenis kendaraan.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');
        $rules = $this->vehicleRules($vehicle instanceof Vehicle ? $vehicle : null);

        if (! $this->user()?->isStaff()) {
            unset($rules['vehicle_type'], $rules['status']);
        }

        return $rules;
    }
}
