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
use App\Http\Resources\Api\v1\UserResource;
use App\Http\Resources\Api\v1\EmailResource;
use App\Http\Resources\Api\v1\FilterResource;
use App\Http\Resources\Api\v1\PermissionResource;
use App\Repository\Contracts\UserRepositoryContract;

class UserService
{
    /**
     * @var \App\Repository\Contracts\UserRepositoryContract
     */
    private $repository;
    
    /**
     * @param  \App\Repository\Contracts\UserRepositoryContract $repository
     * @return void
     */
    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Query Users
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function find(Request $request): Collection
    {
        $result = $this->repository->query($request);
        $result['data'] = UserResource::collection($result['data']);
        return $result;
    }
    
    /**
     * Find User by Id
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return ['data' => new UserResource($this->repository->findWithRelations($id))];
    }
    
    /**
     * Create User
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
     * Update User
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
     * Update or Create User
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
     * Restore User
     *
     * @param  int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $data = $this->repository->find($id);
        if (! $data) {
            $this->repository->restore($id);
            RestoreModelEvent::dispatch(auth()->user(), $this->repository->find($id));
        }
    }

    /**
     * Soft delete User
     *
     * @param  int $id
     * @return void
     */
    public function softDelete(int $id): void
    {
        $data = $this->repository->find($id);
        if ($data) {
            $this->repository->softDelete($id);
            if (auth()->user()->id != $id) {
                SoftDeleteModelEvent::dispatch(auth()->user(), $data);
            }
        }
    }
        
    /**
     * Permanently delete User
     *
     * @param  int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $data = $this->repository->findWithTrashed($id);
        $class = get_class($data);
        $data = $data->toArray();
        $this->repository->delete($id);
        if (auth()->user()->id != $id) {
            DeleteModelEvent::dispatch(auth()->user(), $data, $class);
        }
    }
    
    /**
     * Display User's roles
     *
     * @param  int $id
     * @return array
     */
    public function getUserRolesById(int $id): array
    {
        $roles = $this->repository->roles($id);
        $data = $roles ? RoleResource::collection($roles) : [];
        return ['data' => $data];
    }

    /**
     * Display User's permissions.
     *
     * @param  int $id
     * @return array
     */
    public function getUserPermissionsById(int $id): array
    {
        $permissions = $this->repository->permissions($id);
        $data = $permissions ? PermissionResource::collection($permissions) : [];
        return ['data' => $data];
    }

    /**
     * Display User's emails.
     *
     * @param  int $id
     * @return array
     */
    public function getUserEmailsById(int $id): array
    {
        $emails = $this->repository->emails($id);
        $data = $emails ? EmailResource::collection($emails) : [];
        return ['data' => $data];
    }

    /**
     * Display User personal and global filters
     *
     * @param  int $id
     * @return array
     */
    public function getUserFiltersById(int $id): array
    {
        $filters = $this->repository->filters($id);
        $data = $filters ? FilterResource::collection($filters) : [];
        return ['data' => $data];
    }

    /**
     * Display Watched Users by this User
     *
     * @param  int $id
     * @return array
     */
    public function getWatchedUsersById(int $id): array
    {
        $users = $this->repository->watchedUsers($id);
        $data = $users ? UserResource::collection($users) : [];
        return ['data' => $data];
    }
}
