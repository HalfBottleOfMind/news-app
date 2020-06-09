<?php

declare(strict_types=1);

namespace App\Repository\Eloquent\Traits;

use App\Traits\CamelCaseToSnakeCase;
use Illuminate\Database\Eloquent\Model;

trait SyncRelations
{
    use CamelCaseToSnakeCase;

    /**
     * Sync Model relations
     *
     * @param array $data
     * @param array $camelCaseRelations
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    public function syncRelations(array $data, array $camelCaseRelations, Model $model): array
    {
        foreach ($camelCaseRelations as $relation) {
            $column = $this->camelCaseToSnakeCase($relation);
            if (isset($data[$column])) {
                if (is_array($data[$column])) {
                    $model->$relation()->sync($data[$column]);
                } else {
                    $model->$relation()->sync([]);
                }
                unset($data[$column]);
            }
        }
        return $data;
    }
}
