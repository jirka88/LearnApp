<?php

namespace App\Observers;

use App\Events\ChangeUserInformation;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

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

    /**
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        event(new ChangeUserInformation($user));
        Cache::forget('subjects' . $user->id);
        Cache::forget('userCount');
    }
}
