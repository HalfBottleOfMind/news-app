<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Rules\StringOrArray;
use App\Rules\NoSpeacialChars;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrCreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login' => ['sometimes', 'required', 'string', 'min:4', 'max:20',
                Rule::unique('users', 'login')->ignore($this->user),
                new NoSpeacialChars()
            ],
            'password' => ['sometimes', 'required', 'confirmed', 'string', 'min:10', 'max:50'],
            'first_name' => ['sometimes', 'nullable', 'string'],
            'middle_name' => ['sometimes', 'nullable', 'string'],
            'last_name' => ['sometimes', 'nullable', 'string'],
            'created_at' => ['sometimes', 'required', 'date'],
        ];
    }
}
