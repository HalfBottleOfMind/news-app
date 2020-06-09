<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Models\Role;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Contracts\RoleRepositoryContract;

class RoleRepository extends BaseRepository implements RoleRepositoryContract
{
  
    /**
     * @param \App\Models\Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
