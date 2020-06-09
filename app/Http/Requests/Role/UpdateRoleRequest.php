<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string',
                Rule::unique('roles', 'name')->ignore($this->role)
            ]
        ];
    }
}
