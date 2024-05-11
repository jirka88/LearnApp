<?php

namespace App\Actions;

use App\Http\Components\Localization;

class ChangeLanguageAction
{
    public function handle($language) :Void {
        if (in_array($language, Localization::$supportedLanguages)) {
            Localization::setLocale($language);
        }
    }
}
