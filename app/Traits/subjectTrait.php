<?php

namespace App\Traits;

use App\Http\Components\globalSettings;
use App\Models\Partition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait subjectTrait
{
    public const DEFAULT_VALUE = 'default';

    /**
     * Vrátí předměty / aktualní stránku
     *
     * @return Collection
     */
    public function sortSubjects(?string $sort): Collection
    {
        if ($sort && $sort !== self::DEFAULT_VALUE) {
            $subjects = Partition::where('created_by', Auth()->User()->id)
                ->filter()
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count')->values();
        } else {
                return Partition::where('created_by', Auth()->User()->id)
                    ->paginate(globalSettings::ITEMS_IN_PAGE)
                    ->append('chapter_count');
        }
        return $subjects;
    }
}
