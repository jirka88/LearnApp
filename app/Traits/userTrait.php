<?php

namespace App\Traits;

use App\Http\Components\FilterSubjectSort;
use App\Http\Components\globalSettings;
use App\Models\Partition;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

trait userTrait {
    /**
     * Vrátí počet aktualních sdílení
     *
     * @return int
     */
    public function getActivedShared() {
        if (auth()->user()) {
            return Cache::remember('sharedSubjects' . auth()->user()->id, now()->addMinutes(10), function () {
                $totalUsersCount = 0;
                $shared = auth()->user()->patritions()
                    ->where('accepted', null)
                    ->withCount(['Users' => function ($query) {
                        $query->whereNot('user_id', auth()->user()->id);
                    }])->get();
                $shared->each(function ($item) use (&$totalUsersCount){
                    $totalUsersCount += $item->users_count;
                });
                return $totalUsersCount;
            });
        }
    }

    public function getCurrentColor() {
        return Cache::rememberForever('color', function () {
            return Settings::get('color')->firstOrFail();
        });
    }
}
