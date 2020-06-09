<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpeacialChars implements Rule
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
        if (gettype($value) == 'string') {
            preg_match('/^[A-Za-z0-9._-]+/', $value, $output);
            return $output[0] == $value ? true : false;
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
        return trans('validation.no_speacial_chars');
    }
}
