<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy {
    use HandlesAuthorization;
    //user -> ten kdo to dělá, model -> to koho se to týká

    /**
     * Oprávnění pro správce a operátora
     *
     * @return bool
     */
    public function viewAny(User $user) {
        if ($user->role_id == UserRoles::ADMIN || $user->role_id == UserRoles::OPERATOR) {
            return true;
        }

        return false;
    }

    /**
     * Oprávnění pro správce
     *
     * @return bool
     */
    public function viewAdmin(User $user) {
        if ($user->role_id == UserRoles::ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model) {
        //admin
        if ($user->role_id == UserROles::ADMIN) {
            return true;
        }
        //operator
        elseif ($user->role_id == UserRoles::OPERATOR) {
            return $model->role_id != UserRoles::ADMIN && ($model->role_id != UserRoles::OPERATOR || $user->id == $model->id);
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, User $modal) {
        if ($user->role_id == UserRoles::ADMIN || $modal->patritions->first()?->created_by == $user->id || $modal->patritions->first()?->permission?->permission_id == 2 || $user->patritions->first()?->permission?->permission_id == 3) {
            return true;
        } elseif ($user->role_id == UserRoles::OPERATOR && ($modal->role_id != UserRoles::ADMIN || $modal->role_id != UserRoles::OPERATOR)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model) {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model) {
    }
}
