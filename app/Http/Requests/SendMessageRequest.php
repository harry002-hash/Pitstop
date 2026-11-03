<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'integer',
                // Customer hanya boleh menghubungi bengkel; staff bebas memilih.
                Rule::exists('users', 'id')->where(function (Builder $query): void {
                    if (! $this->user()?->isStaff()) {
                        $query->whereIn('role', User::STAFF_ROLES);
                    }
                }),
                Rule::notIn([$this->user()?->id]),
            ],
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
