<?php

namespace App\Traits;

use App\Http\Components\FilterSubjectSort;
use App\Http\Components\globalSettings;
use App\Models\Partition;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait userTrait
{
    /**
     * Vrátí počet aktualních sdílení
     * @return int
     */
    public function getActivedShared() {
        if(auth()->user()) {
            return Cache::remember('sharedSubjects', now()->addMinutes(10), function () {
                $shared = auth()->user()->patritions()
                    ->withCount(['Users' => function ($query) {
                        $query->whereNot('user_id', auth()->user()->id);
                    }])->get();
                $dataArray = json_decode($shared, true);
                $totalUsersCount = 0;
                foreach ($dataArray as $item) {
                    $totalUsersCount += (int)$item['users_count'];
                }
                return $totalUsersCount;
            });
        }
    }
    public function getCurrentColor()
    {
        return Cache::rememberForever('color', function() {
            return Settings::get('color')->firstOrFail();
        });
    }

    /**
     * Vrátí předměty/stránky/sort
     * @param $sort
     * @return array
     */
    private function indexJson($sort) {
        $filter = new FilterSubjectSort();
        $subjects = $filter->sorting($sort);
        $pages = ceil(count(Partition::all()->where("created_by", auth()->user()->id)) / globalSettings::ITEMS_IN_PAGE);
        return ['subjects' => $subjects, 'pages' => $pages, 'sort' => $sort];
    }
}
