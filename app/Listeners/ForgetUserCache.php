<?php

namespace App\Listeners;

use App\Events\ChangeUserInformation;
use Illuminate\Support\Facades\Cache;

class ForgetUserCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ChangeUserInformation  $event
     * @return void
     */
    public function handle(ChangeUserInformation $event)
    {
        Cache::forget('userLicence' . $event->user->id);
        Cache::forget('userEmail' . $event->user->id);
        Cache::forget('userTypeAccount' . $event->user->id);
        Cache::forget('userRole' . $event->user->id);
    }
}
