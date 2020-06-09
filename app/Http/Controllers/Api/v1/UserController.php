<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Api\v1\UserService;
use App\Http\Requests\User\QueryUserRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateOrCreateUserRequest;

class UserController extends Controller
{

    /**
     * @var \App\Services\Api\v1\UserService
     */
    private $service;
    
    /**
     * Translation name
     *
     * @var string
     */
    private string $translation = 'user';
    
    /**
     * @param  \App\Services\UserService $service
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

     /**
      * Display all required Users
      *
      * @api
      * @param  \App\Http\Requests\User\QueryUserRequest $request
      * @return \Illuminate\Http\Response
      */
    public function index(QueryUserRequest $request): Response
    {
        return response($this->service->find($request));
    }

    /**
     * Store User
     *
     * @api
     * @param  \App\Http\Requests\User\CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request): Response
    {
        $this->service->create($request);
        return response(['message' => trans("notification.store.$this->translation")], 201);
    }

    /**
     * Display User
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): Response
    {
        return response($this->service->getById($id));
    }

    /**
     * Update User
     *
     * @api
     * @param  \App\Http\Requests\User\UpdateUserRequest $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, int $id): Response
    {
        $this->service->update($request, $id);
        return response(['message' => trans("notification.update.$this->translation")]);
    }
    
    /**
     * Update or Create User
     *
     * @api
     * @param  \App\Http\Requests\User\UpdateOrCreateUserRequest $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(UpdateOrCreateUserRequest $request, int $id): Response
    {
        $this->service->updateOrCreateById($request, $id);
        return response(['message' => trans("notification.update.$this->translation")]);
    }

    /**
     * Restore User
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id): Response
    {
        $this->service->restore($id);
        return response(['message' => trans("notification.restore.$this->translation")]);
    }

    /**
     * Soft delete User
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete(int $id): Response
    {
        $this->service->softDelete($id);
        return response(['message' => trans("notification.soft_delete.$this->translation")]);
    }
    
    /**
     * Permanently delete User
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $this->service->destroy($id);
        return response(['message' => trans("notification.destroy.$this->translation")]);
    }

    /**
     * Display User's roles
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function roles(int $id): Response
    {
        return response($this->service->getUserRolesById($id));
    }

    /**
     * Display User's permissions.
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function permissions(int $id): Response
    {
        return response($this->service->getUserPermissionsById($id));
    }
}
