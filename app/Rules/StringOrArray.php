<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StringOrArray implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (gettype($value) == 'string' || gettype($value) == 'array') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.string_or_array');
    }
}
