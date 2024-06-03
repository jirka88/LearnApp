<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationServices {
    public static $supportedLanguages = ['cs', 'en'];

    public function getLocale() {
        return session::get('locale');
    }

    public function setLocale($language) {
        Session::put('locale', $language);
        App::setlocale($language);
    }
}
