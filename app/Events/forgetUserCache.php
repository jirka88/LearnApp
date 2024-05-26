<?php

namespace App\Events;

use App\Listeners\ChangeUserInformation;
use Illuminate\Support\Facades\Cache;

class forgetUserCache
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
     * @param  \App\Listeners\ChangeUserInformation  $event
     * @return void
     */
    public function handle(ChangeUserInformation $event)
    {
        Cache::forget('userLicence' . $event->user->id);
        Cache::forget('userEmail' . $event->user->id);
        Cache::forget('userTypeAccount' . $event->user->id);
    }
}
