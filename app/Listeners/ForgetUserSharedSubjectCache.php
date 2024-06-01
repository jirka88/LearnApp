<?php

namespace App\Listeners;

use App\Events\ChangedUserSharedSubject;
use Illuminate\Support\Facades\Cache;

class ForgetUserSharedSubjectCache
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
     * @param  \App\Events\ChangedUserSharedSubject  $event
     * @return void
     */
    public function handle(ChangedUserSharedSubject $event)
    {
       Cache::forget('sharedSubjects' . $event->user->id);
    }
}
