<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSetUsers extends Controller
{
    public function index() {
        $users = User::orderBy('role_id', 'ASC')->orderby('firstname', 'ASC')->with(['roles', 'accountTypes'])->get();
        return Inertia::render('admin/listUsers', compact('users'));
    }
    public function edit(User $user) {
        $usr = User::with(['roles', 'accountTypes'])->find($user->id);
        $roles = Roles::all();
        $accountTypes = AccountTypes::all();
        return Inertia::render('user/user', compact(['usr', 'roles', 'accountTypes']));
    }
    public function update(User $user, UpdateRequest $updateRequest) {
        $role = $updateRequest->role['id'];
        $typeAccount = $updateRequest->type['id'];
        User::find($user->id)->update([
            'firstname' => $updateRequest->firstname,
            'type_id' => $typeAccount,
            'role_id' => $role,
        ]);
        return redirect()->back()->with('successUpdate', 'Aktualizace proběhla úspěšně!');
    }
    public function getUserSubjects(User $user) {
        $subjects = User::with('patritions')->find($user->id);
        return Inertia::render('admin/listSubjects', compact('subjects'));
    }
}
