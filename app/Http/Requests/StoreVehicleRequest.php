<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isOwner() || auth()->user()->isAdmin());
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'vehicle_name' => ['required', 'string', 'max:100'],
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number'],
            'plate_password' => ['required', 'string', 'min:4', 'max:50'],
            'status' => ['required', Rule::in(array_keys(Vehicle::statuses()))],
        ];
    }
}
