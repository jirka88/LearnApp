<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function edit() {
        return Inertia::render('register', ['value' => 1]);
    }

    /**
     * Přihlášení uživatele
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        $isActive = User::where('email', $credentials['email'])->first();
        if($isActive == null) {
            return redirect()->back()->withErrors(['msg' => 'Účet pod daným emailem neexistuje!']);
        }
        if($isActive['active']) {
            if(!Auth::validate($credentials)) {
                return redirect()->back()->withErrors(['msg' => 'Email nebo heslo není v pořádku!']);
            }
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            Auth::login($user, $request->get('remember'));
            return redirect()->intended("/dashboard");
        }
        else {
            return redirect()->back()->withErrors(['msg' => 'Účet je deaktivován!']);
        }

    }
}
