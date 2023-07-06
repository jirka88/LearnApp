<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class DashboardUserController extends Controller
{
    //return user info
    public function view() {
        $id = auth()->user()->id;
        $usr =  User::with(['roles', 'accountTypes'])->find($id);
        $roles = Roles::all();
        $accountTypes = AccountTypes::all();
        return Inertia::render('user', compact('usr', 'roles', 'accountTypes'));
    }
    public function update(UpdateRequest $updateRequest) {

        $role = $updateRequest->role['id'];
        $typeAccount = $updateRequest->type['id'];
        User::find(auth()->user()->id)->update([
            'firstname' => $updateRequest->firstname,
            'type_id' => $typeAccount,
            'role_id' => $role,
        ]);
        return redirect()->back()->with('successUpdate', 'Aktualizace proběhla úspěšně!');
    }
    public function passwordReset(PasswordResetRequest $passwordResetRequest) {
        if(!Hash::check($passwordResetRequest->oldPassword, auth()->user()->password)) {
            return back()->withErrors(['msg' => 'Staré heslo se liší!']);
        }
        if($passwordResetRequest->oldPassword == $passwordResetRequest->newPassword) {
            return back()->withErrors(['msg' => 'Nové heslo nesmí být stejné jako staré!']);
        }
        User::find(auth()->user()->id)->update([
            'password' => $passwordResetRequest->newPassword,
        ]);
        return redirect()->back()->with('successReset', 'Heslo bylo úspěšně změněno!');
    }

    public function report() {
        return inertia::render('Report');
    }
}
