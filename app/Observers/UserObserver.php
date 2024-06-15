<?php

namespace App\Observers;

use App\Events\ChangeUserInformation;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;

class UserObserver
{
    /**
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        Cache::forget('userCount');
    }
}
