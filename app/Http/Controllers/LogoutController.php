<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class LogoutController extends Controller
{
    /**
     * Odhlášení uživatele
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('login.edit');
    }
}
