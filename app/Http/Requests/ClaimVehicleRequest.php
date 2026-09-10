<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && ! auth()->user()->isOwner();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'plate_number' => ['required', 'string', 'max:20'],
            'plate_password' => ['required', 'string', 'max:50'],
        ];
    }
}
