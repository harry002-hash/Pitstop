<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($this->vehicle)],
            'plate_password' => ['nullable', 'string', 'min:4', 'max:50'],
            'status' => ['required', Rule::in(array_keys(Vehicle::statuses()))],
        ];
    }

    // Gagal validasi -> balik ke index + buka lagi modal baris yang bersangkutan.
    protected function failedValidation(Validator $validator): void
    {
        $vehicle = $this->route('vehicle');

        throw new HttpResponseException(
            redirect()->route('owner.vehicles.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_id', $vehicle instanceof Vehicle ? $vehicle->id : $vehicle)
        );
    }
}
