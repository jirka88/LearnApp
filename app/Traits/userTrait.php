<?php

namespace App\Traits;

use App\Models\User;

trait userTrait
{
    /**
     * Vrátí počet aktualních sdílení
     * @return int
     */
    public function getActivedShared() {
        if(auth()->user()) {
            $shared = User::find(auth()->user()->id)->patritions()->withCount(['Users' => function ($query) {
                $query->whereNot('user_id', auth()->user()->id);
            }])->get();
            $dataArray = json_decode($shared, true);
            $totalUsersCount = 0;
            foreach ($dataArray as $item) {
                $totalUsersCount += (int)$item['users_count'];
            }
            return $totalUsersCount;
        }
    }
}
