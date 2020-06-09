<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use App\Repository\Contracts\RoleRepositoryContract;
use App\Repository\CacheDecorator\BaseRepositoryCache;

class RoleRepositoryCache extends BaseRepositoryCache implements RoleRepositoryContract
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'roles';

    /**
     * @var \App\Repository\Contracts\RoleRepositoryContract
     */
    protected $repository;

    public function __construct(RoleRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
}
