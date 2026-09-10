<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isOwner();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'vehicle_name' => ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($this->vehicle)],
            'plate_password' => ['nullable', 'string', 'min:4', 'max:50'],
            'status' => ['required', Rule::in(array_keys(Vehicle::statuses()))],
        ];
    }
}
