<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSetUsers extends Controller
{
    /**
     * ADMIN -Získání všech uživatelů
     * @return \Inertia\Response
     */
    public function index() {
        $users = User::orderBy('role_id', 'ASC')->orderby('firstname', 'ASC')->with(['roles', 'accountTypes'])->get();
        return Inertia::render('admin/listUsers', compact('users'));
    }

    /**
     *  ADMIN - Získáné informace o uživateli
     * @param User $user
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user) {
        $this->authorize('view', $user);

        $usr = User::with(['roles', 'accountTypes'])->find($user->id);
        $isAdmin = auth()->user()->role_id == 1 ? true : false;
        if($isAdmin) {
            $roles = Roles::all();
        }
        else {
            $roles = Roles::all()->whereNotIn('id', [1,2])->values();
        }
        $accountTypes = AccountTypes::all();
        return Inertia::render('user/user', compact(['usr', 'roles', 'accountTypes']));
    }

    /**
     * ADMIN - Aktualizace uživatele
     * @param User $user
     * @param UpdateRequest $updateRequest
     * @return \Illuminate\Http\RedirectResponse**
     */
    public function update(User $user, UpdateRequest $updateRequest) {

        $role = $updateRequest->role['id'];
        $typeAccount = $updateRequest->type['id'];
        $active = $updateRequest->active['id'];
        User::find($user->id)->update([
            'firstname' => $updateRequest->firstname,
            'type_id' => $typeAccount,
            'role_id' => $role,
            'active' => $active,
        ]);
        return redirect()->back()->with('successUpdate', 'Aktualizace proběhla úspěšně!');
    }

    /**
     * ADMIN - Vymazání uživatele
     * @param User $user
     * @return void
     */
    public function destroy(User $user) {
        $user->patritions()->detach();
        User::destroy($user->id);
    }

    /**
     * ADMIN - Získání předmětů od uživatele v administraci
     * @param User $user
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUserSubjects(User $user) {
        $this->authorize('view', $user);
        $subjects = User::with('patritions')->find($user->id);
        return Inertia::render('admin/listSubjects', compact('subjects'));
    }

    /**
     * ADMIN - Odkázání na vytvoření předmětu u uživatele
     * @param User $user
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createUserSubject(User $user) {
        $this->authorize('view', $user);
        $url = "/dashboard/admin/controll/" . $user->id ."/subject/create";
        return Inertia::render('subjects/createSubjects', compact( 'url'));
    }

    /**
     * ADMIN - vytvoření nového předmětu pod uživatelem
     * @param User $user
     * @param SubjectRequest $subjectRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUserSubject(User $user,  SubjectRequest $subjectRequest) {

        $subject = $subjectRequest->only("name");
        $subject["icon"] = $subjectRequest->icon["iconName"];
        $subject["created_by"] = $user->id;
        $subjectT = Partition::create($subject);

        $user->patritions()->attach($subjectT->id);
        return to_route('adminuser.subjects', $user->id);
    }
}
