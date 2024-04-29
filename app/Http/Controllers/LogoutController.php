<?php

namespace App\Http\Controllers;

use App\Http\Components\Localization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller {
    /**
     * Odhlášení uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {
        $language = Localization::getLocale();
        Session::flush();
        Auth::logout();
        Localization::setLocale($language);

        return redirect()->route('login.edit');
    }
}
