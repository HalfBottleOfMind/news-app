<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class QueryRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'orderby' => ['sometimes', 'required', 'array'],
            'page' => ['sometimes', 'required', 'integer'],
            'limit' => ['sometimes', 'required', 'integer'],
            'query' => ['sometimes', 'required', 'string'],
        ];
    }
}
