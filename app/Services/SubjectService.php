<?php

namespace App\Services;

use App\Http\Components\globalSettings;
use App\Models\Partition;
use App\Models\User;

class SubjectService
{
    /**
     * Vrátí podle sort všechny předměty
     * @param Partition $subject
     * @param String|null $sort
     * @param $user_id
     * @return array
     */
    public function index(Partition $subject, ?String $sort, $user_id, ?int $actual_page, ?String $url, ?array $query) {
        $subjects = $subject->sortSubjects($sort, $actual_page, $url, $query);
        $pages = ceil(count(Partition::all()->where('created_by', $user_id)) / globalSettings::ITEMS_IN_PAGE);
        return ['subjects' => $subjects, 'pages' => $pages, 'sort' => $sort];
    }
}
