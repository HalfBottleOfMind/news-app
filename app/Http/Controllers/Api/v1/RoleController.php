<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Api\v1\RoleService;
use App\Http\Requests\Role\QueryRoleRequest;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\UpdateOrCreateRoleRequest;

class RoleController extends Controller
{

    /**
     * @var \App\Services\Api\v1\RoleService
     */
    private $service;
    
    /**
     * Translation name
     *
     * @var string
     */
    private string $translation = 'role';
    
    /**
     * @param  \App\Services\RoleService $service
     * @return void
     */
    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

     /**
      * Display all required Roles
      *
      * @api
      * @param  \App\Http\Requests\Role\QueryRoleRequest $request
      * @return \Illuminate\Http\Response
      */
    public function index(QueryRoleRequest $request): Response
    {
        return response($this->service->find($request));
    }

    /**
     * Store Role
     *
     * @api
     * @param  \App\Http\Requests\Role\CreateRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request): Response
    {
        $this->service->create($request);
        return response(['message' => trans("notification.store.$this->translation")], 201);
    }

    /**
     * Display Role
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
     * Update Role
     *
     * @api
     * @param  \App\Http\Requests\Role\UpdateRoleRequest $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, int $id): Response
    {
        $this->service->update($request, $id);
        return response(['message' => trans("notification.update.$this->translation")]);
    }
    
    /**
     * Update or Create Role
     *
     * @api
     * @param  \App\Http\Requests\Role\UpdateOrCreateRoleRequest $request
     * @param  int     $id
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(UpdateOrCreateRoleRequest $request, int $id): Response
    {
        $this->service->updateOrCreateById($request, $id);
        return response(['message' => trans("notification.update.$this->translation")]);
    }
    
    /**
     * Permanently delete Role
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
     * Display Role's roles
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function roles(int $id): Response
    {
        return response($this->service->getRoleRolesById($id));
    }

    /**
     * Display Role's permissions.
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function permissions(int $id): Response
    {
        return response($this->service->getRolePermissionsById($id));
    }

    /**
     * Display Role's emails.
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function emails(int $id): Response
    {
        return response($this->service->getRoleEmailsById($id));
    }

    /**
     * Display Role personal and global filters
     *
     * @api
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function filters(int $id): Response
    {
        return response($this->service->getRoleFiltersById($id));
    }

    /**
     * Issue a new token for a Role.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(Request $request): Response
    {
        $token = auth()->Role()->createToken($request->name)->plainTextToken;
        return response($token);
    }

    /**
     * Revoke Role token
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function revokeToken(Request $request): Response
    {
        auth()->Role()->tokens()->where('name', $request->name)->delete();
        return response()->noContent();
    }
}
