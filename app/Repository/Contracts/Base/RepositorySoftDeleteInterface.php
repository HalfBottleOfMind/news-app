<?php

declare(strict_types=1);

namespace App\Repository\Contracts\Base;

use Illuminate\Database\Eloquent\Model;

interface RepositorySoftDeleteInterface
{
    
    /**
     * Find Model by ID with trashed
     *
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findWithTrashed(int $id): ?Model;

    /**
     * Soft deleting the Model.
     *
     * @param  int $id
     * @return void
     */
    public function softDelete(int $id): void;

    /**
     * Restore soft deleted Model.
     *
     * @param  int $id
     * @return void
     */
    public function restore(int $id): void;
}
