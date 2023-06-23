<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
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
        $usr =  User::with('roles')->find($id);
        $roles = Roles::all();
        return Inertia::render('user', compact('usr', 'roles'));
    }
    public function update() {

    }
    public function passwordReset(PasswordResetRequest $passwordResetRequest) {
        if(!Hash::check($passwordResetRequest->oldPassword, auth()->user()->password)) {
            return back()->withErrors(['msg' => 'Staré heslo se liší!']);
        }
        User::find(auth()->user()->id)->update([
            'password' => $passwordResetRequest->newPassword,
        ]);
        return redirect()->back()->with(['success'=> 'Heslo bylo úspěšně změněno!']);
    }
}
