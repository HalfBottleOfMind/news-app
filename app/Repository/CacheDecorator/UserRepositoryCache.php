<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Contracts\UserRepositoryContract;
use App\Repository\CacheDecorator\BaseRepositoryCache;
use App\Repository\Contracts\Base\RepositorySoftDeleteInterface;

class UserRepositoryCache extends BaseRepositoryCache implements UserRepositoryContract, RepositorySoftDeleteInterface
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'users';

    /**
     * @var \App\Repository\Contracts\UserRepositoryContract
     */
    protected $repository;

    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Find User by ID with trashed
     *
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findWithTrashed(int $id): ?Model
    {
        return $this->repository->findWithTrashed($id);
    }


    /**
     * Restore soft deleted User.
     *
     * @param  int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $this->repository->restore($id);
    }

    /**
     * Soft deleting the User.
     *
     * @param  int $id
     * @return void
     */
    public function softDelete(int $id): void
    {
        Cache::tags($this->cache)->forget("$this->cache.$id");
        $this->repository->softDelete($id);
    }

    /**
     * Get User Roles
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles(int $id): ?Collection
    {
        return $this->repository->roles($id);
    }

    /**
     * Get User Permissions
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions(int $id): ?Collection
    {
        return $this->repository->permissions($id);
    }

    /**
     * Get User Emails
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function emails(int $id): ?Collection
    {
        return $this->repository->emails($id);
    }
    
    /**
     * Get User personal and global filters
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filters(int $id): ?Collection
    {
        return $this->repository->filters($id);
    }

    /**
     * Display Watched Users by this User
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function watchedUsers(int $id): ?Collection
    {
        return $this->repository->watchedUsers($id);
    }
}
