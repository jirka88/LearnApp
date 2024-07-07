<?php

namespace App\Http\Controllers;

use App\Services\LocalizationServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller {
    /**
     * Odhlášení uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(LocalizationServices $services) {
        $language = $services->getLocale();
        Session::flush();
        Auth::logout();
        $services->setLocale($language);

        return redirect()->route('login.edit');
    }
}
