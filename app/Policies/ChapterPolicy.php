<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
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
     * @param \App\Models\Chapter $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chapter $chapter)
    {

        if($user->role_id == Roles::ADMIN || $user->id == $chapter->Partition->created_by || ($user->patritions->where('permission.partition_id', $chapter->Partition->id)->where('permission.accepted', 1)->first() != null )) {
            return true;
        }
        else if($user->role_id == Roles::OPERATOR && ($chapter->Partition->Users->first()->role_id != Roles::ADMIN && $chapter->Partition->Users->first()->role_id != Roles::OPERATOR)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Chapter $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chapter $chapter)
    {
        //zjištění zdali předmět není vytvořený správcem
        $usr = User::where('id', $chapter->partition->created_by)->first();
        if($user->id == $chapter->partition->created_by || $user->role_id == Roles::ADMIN || $chapter->partition->Users->first()?->permission->permission_id == 2 || $chapter->partition->Users->first()?->permission->permission_id == 3 ) {
            return true;
        }
        else if ($user->role_id == Roles::OPERATOR) {
            return (int)$usr->role_id !== Roles::ADMIN && (int)$usr->role_id !== Roles::OPERATOR;
        }
        else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Chapter $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chapter $chapter)
    {
        //
    }


}
