<?php
namespace App\Traits;

use App\Http\Components\Filters;
use App\Http\Components\globalSettings;
use App\Models\Partition;
use Illuminate\Support\Collection;

trait subjectTrait {
    public const DEFAULT_VALUE = 'default';

    /**
     * Vrátí předměty / aktualní stránku
     *
     * @return Collection
     */
    public function sortSubjects(?String $sort) :Collection{
        if ($sort && $sort !== self::DEFAULT_VALUE) {
            $subjects = Partition::orderBy('name', $sort)
                ->where('created_by', Auth()->User()->id)
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count')->values();
        } else {
            $subjects = Partition::where('created_by', Auth()->User()->id)
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count');
        }
        return $subjects;
    }
}
