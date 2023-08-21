<?php

namespace App\Policies;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;
    //user -> ten kdo to dělá, model -> to koho se to týká
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if($user->role_id == Roles::ADMIN || $user->role_id == Roles::OPERATOR) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        //admin
        if($user->role_id == Roles::ADMIN) {
            return true;
        }
        //operator
        else if($user->role_id == Roles::OPERATOR) {
            return $model->role_id != Roles::ADMIN && ($model->role_id != Roles::OPERATOR || $user->id == $model->id);
        }
        else {
            return false;
        }
    }
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, User $modal)
    {
        if($user->role_id == Roles::ADMIN || $modal->patritions->first()->created_by == auth()->user()->id || $modal->patritions->first()->permission?->permission_id == 2 ||  $user->patritions->first()->permission?->permission_id ==3) {
            return true;
        }
        else {
            return false;
        }

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
    }


}
