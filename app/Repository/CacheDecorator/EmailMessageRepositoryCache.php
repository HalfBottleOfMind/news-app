<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use App\Models\Email\Email;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\CacheDecorator\BaseRepositoryCache;
use App\Repository\Contracts\EmailMessageRepositoryContract;
use App\Repository\Contracts\Base\RepositorySoftDeleteInterface;

class EmailMessageRepositoryCache extends BaseRepositoryCache implements EmailMessageRepositoryContract, RepositorySoftDeleteInterface
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'email_messages';

    /**
     * @var \App\Repository\Contracts\EmailMessageRepositoryContract
     */
    protected $repository;

    public function __construct(EmailMessageRepositoryContract $repository)
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
     * Get Inbox Email
     *
     * @param  int $id
     * @return \App\Models\Email\Email
     */
    public function inbox(int $id): ?Email
    {
        return $this->repository->inbox($id);
    }

    /**
     * Get Outbox Email
     *
     * @param  int $id
     * @return \App\Models\Email\Email
     */
    public function outbox(int $id): ?Email
    {
        return $this->repository->outbox($id);
    }
}
