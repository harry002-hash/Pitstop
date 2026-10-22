<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\VehicleValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    use VehicleValidationRules;

    public function authorize(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return $this->vehicleRules();
    }
}
