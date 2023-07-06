<?php

namespace App\Policies;

use App\Models\Partition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partition  $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Partition $partition)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partition  $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Partition $partition)
    {
        return $user->id_admin || (auth()->check() && auth()->user()->id == $partition->created_by);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partition  $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Partition $partition)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partition  $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Partition $partition)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partition  $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Partition $partition)
    {
        //
    }
}
