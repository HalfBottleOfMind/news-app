<?php

namespace App\Services\Api\v1;

use Illuminate\Http\Request;
use App\Events\CreateModelEvent;
use App\Events\DeleteModelEvent;
use App\Events\UpdateModelEvent;
use App\Events\RestoreModelEvent;
use Illuminate\Support\Collection;
use App\Events\SoftDeleteModelEvent;
use App\Http\Resources\Api\v1\RoleResource;
use App\Repository\Contracts\RoleRepositoryContract;

class RoleService
{
    /**
     * @var \App\Repository\Contracts\RoleRepositoryContract
     */
    private $repository;
    
    /**
     * @param  \App\Repository\Contracts\RoleRepositoryContract $repository
     * @return void
     */
    public function __construct(RoleRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Query Roles
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function find(Request $request): Collection
    {
        $result = $this->repository->query($request);
        $result['data'] = RoleResource::collection($result['data']);
        return $result;
    }
    
    /**
     * Find Role by Id
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return ['data' => new RoleResource($this->repository->findWithRelations($id))];
    }
    
    /**
     * Create Role
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function create(Request $request): void
    {
        $model = $this->repository->create($request);
        CreateModelEvent::dispatch(auth()->user(), $model);
    }
        
    /**
     * Update Role
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id): void
    {
        $oldData = $this->repository->find($id);
        $this->repository->update($id, $request);
        UpdateModelEvent::dispatch(auth()->user(), $this->repository->find($id), $oldData);
    }

    /**
     * Update or Create Role
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     */
    public function updateOrCreateById(Request $request, int $id): void
    {
        $this->repository->updateOrCreate($id, $request);
    }
        
    /**
     * Permanently delete Role
     *
     * @param  int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $data = $this->repository->find($id);
        $class = get_class($data);
        $data = $data->toArray();
        $this->repository->delete($id);
        if (auth()->user()->id != $id) {
            DeleteModelEvent::dispatch(auth()->user(), $data, $class);
        }
    }
}
