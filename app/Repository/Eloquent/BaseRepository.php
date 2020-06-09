<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Facades\App\Repository\Eloquent\Query\Query;
use App\Repository\Contracts\Base\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * Instance of Eloquent Model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * Get a collection of filtered Model instances
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function query(Request $request): Collection
    {
        $page = $request->query('page') ? (int) $request->query('page') : 1;
        $limit = $request->query('limit') || $request->query('limit') === '0' ? (int) $request->query('limit') : 20;
        return Query::model($this->model)
            ->page($page)
            ->limit($limit)
            ->fields($request->query())
            ->path($request->fullUrl())
            ->get();
    }

    /**
     * Create Model
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Request $request): Model
    {
        $model = $this->model->create($request->validated());
        $model->fresh();
        return $model;
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
        $this->model->find($id)->update($request->validated());
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
        $this->model->updateOrCreate(['id' => $id], $request->validated());
    }
    
    /**
     * Find Model by ID with relations
     *
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findWithRelations(int $id): ?Model
    {
        return $this->model->withAll()->find($id);
    }

    /**
     * Find Model by ID
     *
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
   
    /**
     * Delete Model by ID
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->model->find($id)->delete();
    }
}
