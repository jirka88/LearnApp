<?php

namespace App\Http\Components;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public static $supportedLanguages = ['cs', 'en'];
    public static function getLocale() {
        return session::get('locale');
    }
    public static function setLocale($language) {
        Session::put('locale', $language);
        App::setlocale($language);
    }
}
