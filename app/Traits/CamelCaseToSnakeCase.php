<?php

declare(strict_types=1);

namespace App\Traits;

trait CamelCaseToSnakeCase
{
    /**
     * Transforms a CamelCase string to snake case
     *
     * @param  string $string
     * @return string
     */
    public function camelCaseToSnakeCase(string $string): string
    {
        if (preg_match('/[A-Z]/', $string) === 0) {
            return $string;
        }
        $pattern = '/([a-z])([A-Z])/';
        return strtolower(preg_replace_callback($pattern, function ($replace): string {
            return $replace[1] . "_" . strtolower($replace[2]);
        }, $string));
    }
}
