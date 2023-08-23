<?php

namespace App\Policies;

use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use http\Env\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Partition $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Partition $partition)
    {
        //majitel/sdílení nebo ADMIN mají přístup
        if ($partition->created_by == auth()->user()->id || $user->role_id == Roles::ADMIN || $partition->permission?->accepted == true) {
            return true;
        } //operátor pokud není správcová má přístup
        else if ($user->role_id == Roles::OPERATOR) {
            return (int)$partition->Users->first()->role_id !== Roles::ADMIN && (int)$partition->Users->first()->role_id !== Roles::OPERATOR;
        }
        else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        dd('test');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Partition $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Partition $partition)
    {
        $adminSubject = User::where('id', $partition->created_by)->first();
        //zobrazení admin nebo majitel předmětu
        if ($user->role_id == Roles::ADMIN || (auth()->check() && auth()->user()->id == $partition->created_by)) {
            return true;
        } //zobrazení operátora --> nemůže zobrazit správcův předmět a jiný operátorův
        else if ($user->role_id == Roles::OPERATOR) {
            return $adminSubject->role_id !== Roles::ADMIN && $adminSubject->role_id !== Roles::OPERATOR;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Partition $partition
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Partition $partition)
    {
        //
    }

}
