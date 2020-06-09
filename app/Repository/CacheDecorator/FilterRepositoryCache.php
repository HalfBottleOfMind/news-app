<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use App\Repository\Contracts\FilterRepositoryContract;
use App\Repository\CacheDecorator\BaseRepositoryCache;

class FilterRepositoryCache extends BaseRepositoryCache implements FilterRepositoryContract
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'filters';

    /**
     * @var \App\Repository\Contracts\FilterRepositoryContract
     */
    protected $repository;

    public function __construct(FilterRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
}
