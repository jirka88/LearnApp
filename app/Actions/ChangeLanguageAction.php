<?php

namespace App\Actions;

use App\Services\LocalizationServices;

class ChangeLanguageAction
{
    private $service;
    public function __construct(LocalizationServices $service)
    {
        $this->service = $service;
    }

    public function handle($language) :Void {
        if (in_array($language, LocalizationServices::$supportedLanguages)) {
            $this->service->setLocale($language);
        }
    }
}
