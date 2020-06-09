<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Models\Filter;
use App\Models\WatchUser;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRelationships;
    
    /**
     * An array of searchable attributes
     *
     * @var array
     */
    public array $searchable = [
        'id',
        'login',
        'first_name',
        'middle_name',
        'last_name',
        'created_at',
    ];
    
    /**
     * Array of searchable attributes that will be searched via orWhere option
     *
     * @var array
     */
    public array $checkable = [
        'permissions__slug'
    ];
    
    /**
     * Array of searchable attributes that will be searched via where option
     *
     * @var array
     */
    public array $selectable = [
        'roles__name'
    ];

    /**
     * An array of attributes that are not allowed for a mass assignment
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Turns on/off default laravel timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'access',
        'remember_token',
        'pivot',
        'deleted_at'
    ];

    /**
     * An array of attributes that will be added to response
     *
     * @var array
     */
    protected $appends = ['full_name', 'access'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
    ];
    
    /**
     * Relations that can be synced
     *
     * @var array
     */
    public array $syncedRelations = [
        'roles',
        'watchedUsers',
    ];
    
    /**
     * Joins all relations (Used for a query method)
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithJoins(Builder $query): Builder
    {
        return $query->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftjoin('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->leftjoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->addSelect('users.*');
    }
 
    /**
     * Return all relations (Used for a query method)
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAll(Builder $query): Builder
    {
        return $query->with(['roles', 'permissions']);
    }

    /**
     * User's full name
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $firstName = $this->first_name ? mb_convert_case($this->first_name, MB_CASE_TITLE, 'UTF-8') : '';
        $middleName = $this->middle_name ? mb_convert_case($this->middle_name, MB_CASE_TITLE, 'UTF-8') : '';
        $lastName = $this->last_name ? mb_convert_case($this->last_name, MB_CASE_TITLE, 'UTF-8') : '';
        return ($lastName ? $lastName . ' ' : '') . ($firstName ? $firstName . ' ' : '') . ($middleName ?? '');
    }

    /**
     * User's Access (It's just an array of permissions)
     *
     * @return array
     */
    public function getAccessAttribute(): array
    {
        return array_map(
            function ($permission) {
                return $permission['slug'];
            },
            $this->permissions->toArray()
        );
    }

    /**
     * Assigned roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function roles(): Relation
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Available permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function permissions(): Relation
    {
        return $this->hasManyDeep(Permission::class, ['role_user', Role::class, 'permission_role']);
    }
}
