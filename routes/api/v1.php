<?php

use App\Models\Role;
use App\Models\User;
use App\Facades\ApiRoute;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\RoleController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Resources\Api\v1\PermissionResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'users'], function () {
    Route::post('token/create', [UserController::class, 'issueToken'])->name('users.token.issue');
    Route::post('token/delete', [UserController::class, 'revokeToken'])->name('users.token.revoke');
});

ApiRoute::name('users')
    ->slug('user')
    ->controller(UserController::class)
    ->policy(User::class)
    ->add(['roles', 'permissions'])
    ->resource()
    ->withSoftDelete()
    ->create();

ApiRoute::name('roles')
    ->slug('role')
    ->controller(RoleController::class)
    ->policy(Role::class)
    ->resource()
    ->create();

// Static data
Route::get('permissions', function () {
    return response(PermissionResource::collection(Permission::all()));
})->name('permissions.index');
