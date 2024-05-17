<?php

namespace App\Traits;

use App\Http\Components\globalSettings;
use App\Models\Partition;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait subjectTrait
{
    public const DEFAULT_VALUE = 'default';

    /**
     * Vrátí předměty / aktualní stránku
     *
     * @return
     */
    public function sortSubjects(?string $sort, ?int $currentPage, ?String $url, ?array $query)
    {
        $page = $currentPage == null ? 1 : $currentPage;
        if ($sort && $sort !== self::DEFAULT_VALUE) {
            $data = Partition::filter()
                ->where('created_by', Auth()->User()->id)
                ->paginate(globalSettings::ITEMS_IN_PAGE)
                ->append('chapter_count')->values();
            $subjects = new LengthAwarePaginator($data, count($data), globalSettings::ITEMS_IN_PAGE, $page, [
                'path' => $url,
                'query' => $query
            ]);
        } else {
            $data = Partition::where('created_by', Auth()->User()->id)
                    ->paginate(globalSettings::ITEMS_IN_PAGE)
                    ->append('chapter_count');
            $subjects = new LengthAwarePaginator($data, count($data), globalSettings::ITEMS_IN_PAGE, $page, [
                'path' => $url,
                'query' => $query
            ]);
        }
        return $subjects;
    }
}
