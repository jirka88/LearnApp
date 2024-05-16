<?php

namespace App\Observers;

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
        Cache::forget('userCount');
    }
}
