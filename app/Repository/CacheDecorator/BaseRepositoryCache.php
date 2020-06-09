<?php

declare(strict_types=1);

namespace App\Repository\CacheDecorator;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Repository\Contracts\Base\RepositoryInterface;

abstract class BaseRepositoryCache implements RepositoryInterface
{

    protected const TTL = 86400;

    /**
     * Cache prefix
     *
     * @var string
     */
    protected string $cache;

    /**
     * Get a collection of filtered Model instances
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function query(Request $request): Collection
    {
        $key = $request->all() ? http_build_query($request->all()) : 'all';
        return Cache::tags("$this->cache.query")
            ->remember("$this->cache.query." . strtolower($key), self::TTL, fn() => $this->repository->query($request));
    }

    /**
     * Create Model
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Request $request): Model
    {
        Cache::tags("$this->cache.query")->flush();
        return $this->repository->create($request);
    }
 
    /**
     * Update Model
     *
     * @param  int   $id
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function update(int $id, Request $request): void
    {
        Cache::tags($this->cache)->forget("$this->cache.$id");
        Cache::tags("$this->cache.query")->flush();
        $this->repository->update($id, $request);
    }

    /**
     * Update or create a Model with the given ID
     *
     * @param  int   $id
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function updateOrCreate(int $id, Request $request): void
    {
        Cache::tags($this->cache)->forget("$this->cache.$id");
        $this->repository->updateOrCreate($id, $request);
    }
    
    /**
     * Find Model by ID with relations
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findWithRelations(int $id): ?Model
    {
        return Cache::tags($this->cache)->remember("$this->cache.$id", self::TTL, fn() => $this->repository->findWithRelations($id));
    }

    /**
     * Find Model by ID
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): ?Model
    {
        return Cache::tags($this->cache)->remember("$this->cache.$id", self::TTL, fn() => $this->repository->find($id));
    }
   
    /**
     * Delete Model by ID
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Cache::tags($this->cache)->forget("$this->cache.$id");
        $this->repository->delete($id);
    }
}
