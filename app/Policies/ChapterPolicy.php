<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user) {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chapter $chapter) {

        if ($user->role_id == UserRoles::ADMIN || $user->id == $chapter->Partition->created_by || ($user->patritions->where('permission.partition_id', $chapter->Partition->id)->where('permission.accepted', 1)->first() != null)) {
            return true;
        } elseif ($user->role_id == UserRoles::OPERATOR && ($chapter->Partition->Users->first()->role_id != UserRoles::ADMIN && $chapter->Partition->Users->first()->role_id != UserRoles::OPERATOR)) {
            return true;
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

    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chapter $chapter) {
        //zjištění zdali předmět není vytvořený správcem
        $usr = User::where('id', $chapter->partition->created_by)->first();
        if ($user->id == $chapter->partition->created_by || $user->role_id == UserRoles::ADMIN || ($chapter->partition->Users->first()?->permission->permission_id !== null || $chapter->partition->Users->first()?->permission->permission_id != 1)) {
            return true;
        } elseif ($user->role_id == UserRoles::OPERATOR) {
            return (int) $usr->role_id !== UserRoles::ADMIN && (int) $usr->role_id !== UserRoles::OPERATOR;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chapter $chapter) {
        //
    }
}
