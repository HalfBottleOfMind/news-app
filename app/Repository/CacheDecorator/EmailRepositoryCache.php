<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use App\Models\Email\EmailCredential;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Contracts\EmailRepositoryContract;
use App\Repository\CacheDecorator\BaseRepositoryCache;

class EmailRepositoryCache extends BaseRepositoryCache implements EmailRepositoryContract
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'emails';

    /**
     * @var \App\Repository\Contracts\EmailRepositoryContract
     */
    protected $repository;

    public function __construct(EmailRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Get Email users
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function users(int $id): ?Collection
    {
        return $this->repository->users($id);
    }

    /**
     * Get Email Credential
     *
     * @param  int $id
     * @return \App\Models\Email\EmailCredential
     */
    public function credentials(int $id): ?EmailCredential
    {
        return $this->repository->credentials($id);
    }

    /**
     * Get Email Inbox messages
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function inbox(int $id): ?Collection
    {
        return $this->repository->inbox($id);
    }

    /**
     * Get Email Outbox messages
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function outbox(int $id): ?Collection
    {
        return $this->repository->outbox($id);
    }
}
