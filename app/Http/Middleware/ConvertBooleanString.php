<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertBooleanString extends TransformsRequest
{
    protected function transform($key, $value)
    {
        if (mb_strtolower(strval($value)) === 'true') {
            return true;
        }
        if (mb_strtolower(strval($value)) === 'false') {
            return false;
        }
        return $value;
    }
}
