<?php

namespace App\Observers;

use App\Models\Chapter;
use Illuminate\Support\Facades\Cache;

class ChapterObserver
{
    /**
     * Handle the Chapter "created" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function created(Chapter $chapter)
    {
        Cache::forget('chapterAllCount');
    }
    /**
     * Handle the Chapter "deleted" event.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return void
     */
    public function deleted(Chapter $chapter)
    {
        Cache::forget('chapterAllCount');
    }

}
