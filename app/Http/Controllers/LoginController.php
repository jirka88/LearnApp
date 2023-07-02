<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function edit() {
            return Inertia::render('register', ['value' => 1]);
    }
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        if(!Auth::validate($credentials)) {
            return redirect()->back()->withErrors(['msg' => 'Email nebo heslo není v pořádku!']);
        }
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user, $request->get('remember'));
        return redirect()->intended("/dashboard");
    }
}
