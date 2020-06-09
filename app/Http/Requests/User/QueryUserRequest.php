<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Rules\NoSpeacialChars;
use Illuminate\Foundation\Http\FormRequest;

class QueryUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'required', 'integer'],
            'login' => ['sometimes', 'required', 'string', 'min:4', 'max:20', new NoSpeacialChars()],
            'first_name' => ['sometimes', 'nullable', 'string'],
            'middle_name' => ['sometimes', 'nullable', 'string'],
            'last_name' => ['sometimes', 'nullable', 'string'],
            'created_at_to' => ['sometimes', 'required', 'date'],
            'created_at_from' => ['sometimes', 'required', 'date'],
            'roles__name' => ['sometimes', 'required', 'array'],
            'roles__name.*' => ['sometimes', 'required', 'string'],
            'permissions__slug' => ['sometimes', 'required', 'array'],
            'permissions__slug.*' => ['sometimes', 'required', 'string'],
            'orderby' => ['sometimes', 'required', 'array'],
            'page' => ['sometimes', 'required', 'integer'],
            'limit' => ['sometimes', 'required', 'integer'],
            'query' => ['sometimes', 'required', 'string'],
        ];
    }
}
