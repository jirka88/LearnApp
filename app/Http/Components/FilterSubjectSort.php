<?php

namespace App\Http\Components;

use App\Models\Partition;
use Illuminate\Http\JsonResponse;
use Ramsey\Collection\Collection;

class FilterSubjectSort extends Filters
{
    public function sorting($sort)
    {
        if($sort && $sort !== Filters::DEFAULT_VALUE) {
            $subjects = Partition::orderBy('name', $sort)
                ->where('created_by', Auth()->User()->id)
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count')->values();
        }
        else {
            $subjects = Partition::where('created_by', Auth()->User()->id)
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count');
        }
        return $subjects;
    }
}
