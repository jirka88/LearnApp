<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Partition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartitionPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Partition $partition) {
        //majitel/sdílení nebo ADMIN mají přístup
        if ($partition->created_by == auth()->user()->id || $user->role_id == UserRoles::ADMIN || $partition->permission?->accepted == true) {
            return true;
        } //operátor pokud není správcová má přístup
        elseif ($user->role_id == UserRoles::OPERATOR) {
            return (int) $partition->Users->first()->role_id !== UserRoles::ADMIN && (int) $partition->Users->first()->role_id !== UserRoles::OPERATOR;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user) {
        dd('test');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Partition $partition) {
        $adminSubject = User::where('id', $partition->created_by)->first();
        //zobrazení admin nebo majitel předmětu
        if ($user->role_id == UserRoles::ADMIN || (auth()->check() && auth()->user()->id == $partition->created_by)) {
            return true;
        } //zobrazení operátora --> nemůže zobrazit správcův předmět a jiný operátorův
        elseif ($user->role_id == UserRoles::OPERATOR) {
            return $adminSubject->role_id !== UserRoles::ADMIN && $adminSubject->role_id !== UserRoles::OPERATOR;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Partition $partition) {
        //
    }
}
