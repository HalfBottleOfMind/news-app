<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Eloquent\Traits\FileTrait;
use App\Repository\Eloquent\Traits\SyncRelations;
use App\Repository\Contracts\UserRepositoryContract;
use App\Repository\Contracts\Base\RepositorySoftDeleteInterface;

class UserRepository extends BaseRepository implements UserRepositoryContract, RepositorySoftDeleteInterface
{
    use FileTrait;
    use SyncRelations;

    /**
     * @param \App\Models\User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
     /**
     * Create User
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    public function create(Request $request): User
    {
        DB::beginTransaction();
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $model = $this->model->create($data);
        if (isset($this->model->syncedRelations)) {
            $data = $this->syncRelations($data, $this->model->syncedRelations, $model);
        };
        $model->update();
        DB::commit();
        return $model;
    }

    /**
     * Update User
     *
     * @param int   $id
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function update(int $id, Request $request): void
    {
        DB::beginTransaction();
        $model = $this->model->find($id);
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        if (isset($this->model->syncedRelations)) {
            $data = $this->syncRelations($data, $this->model->syncedRelations, $model);
        };
        $model->update($data);
        DB::commit();
    }

    /**
     * Update or create a User with the given ID
     *
     * @param  int   $id
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function updateOrCreate(int $id, Request $request): void
    {
        DB::beginTransaction();
        $model = $this->model->find($id);
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        if (isset($this->model->syncedRelations)) {
            $data = $this->syncRelations($data, $this->model->syncedRelations, $model);
        };
        $model->updateOrCreate(['id' => $id], $data);
        DB::commit();
    }

    /**
     * Find User by ID with trashed
     *
     * @param  int $id
     * @return \App\Models\User
     */
    public function findWithTrashed(int $id): ?User
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * Restore soft deleted User.
     *
     * @param  int $id
     * @return void
     */
    public function restore(int $id): void
    {
        $this->model->withTrashed()->find($id)->restore();
    }

    /**
     * Soft deleting the User.
     *
     * @param  int $id
     * @return void
     */
    public function softDelete(int $id): void
    {
        $this->model->whereId($id)->delete();
    }

     /**
     * Delete User by ID
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $model = $this->model->withTrashed()->find($id);
        $this->deleteDirectory("/users/$model->id");
        $model->forceDelete();
    }

    /**
     * Get User Roles
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles(int $id): ?Collection
    {
        return $this->model->find($id)->roles()->get();
    }

    /**
     * Get User Permissions
     *
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissions(int $id): ?Collection
    {
        return $this->model->find($id)->permissions()->get();
    }
}
