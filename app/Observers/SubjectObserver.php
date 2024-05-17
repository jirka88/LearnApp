<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Models\Partition;
use Illuminate\Support\Facades\Cache;

class SubjectObserver
{
    public function created(Partition $partition)
    {
        Cache::forget('subjects' . auth()->user()->id);
    }
    public function deleted(Partition $partition)
    {
        Cache::forget('subjects' . auth()->user()->id);
    }
}
