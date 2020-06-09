<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use App\Models\Email\Email;
use App\Repository\CacheDecorator\BaseRepositoryCache;
use App\Repository\Contracts\EmailCredentialRepositoryContract;

class EmailCredentialRepositoryCache extends BaseRepositoryCache implements EmailCredentialRepositoryContract
{
    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache = 'email_credentials';

    /**
     * @var \App\Repository\Contracts\EmailCredentialRepositoryContract
     */
    protected $repository;

    public function __construct(EmailCredentialRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get Email
     *
     * @param  int $id
     * @return \App\Models\Email\Email
     */
    public function email(int $id): ?Email
    {
        return $this->repository->email($id);
    }
}
