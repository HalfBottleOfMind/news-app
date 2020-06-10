<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return in_array('read_roles', $user->access);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function view(User $user, Role $role): bool
    {
        return in_array('read_roles', $user->access);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return in_array('create_roles', $user->access);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function update(User $user, Role $role): bool
    {
        return in_array('update_roles', $user->access);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return bool
     */
    public function delete(User $user, Role $role): bool
    {
        return in_array('delete_roles', $user->access);
    }
}
