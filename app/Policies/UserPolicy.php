<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return in_array('read_users', $user->access);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User $user
     * @param  User $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        logger('i am here');
        return ($user->id == $model->id || in_array('read_users', $user->access));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return in_array('create_users', $user->access);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  User $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return ($user->id == $model->id || in_array('update_users', $user->access));
    }

    /**
     * Determine whether the user can soft delete the model.
     *
     * @param  User $user
     * @param  User $model
     * @return bool
     */
    public function softDelete(User $user, User $model): bool
    {
        return ($user->id == $model->id || in_array('delete_users', $user->access));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  User $model
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return in_array('create_users', $user->access);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  User $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return in_array('delete_users', $user->access);
    }
}
