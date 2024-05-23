<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Http\Components\globalSettings;
use App\Models\Chapter;
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

    /**
     * Vrací předmět s kapitolamy, stránkováním a oprávněním
     * @param $subjectModel
     * @param $slug
     * @param $user_id
     * @param $user_role_id
     * @return array
     */
    public function show(Partition $subjectModel, String $slug, int $user_id, int $user_role_id) : array  {
        $subject = $subjectModel->getSubjectBySlug($slug);
        $pShare = $subject->Users()->find($user_id, ['user_id'])?->permission;
        $sharingUsr = [];
        if ($pShare === null) {
            if ($user_role_id == UserRoles::ADMIN || $user_role_id == UserRoles::OPERATOR) {
                $subject['permission'] = $subject->Users()->find($subject->created_by)->permission;
            }
        } else {
            $subject['permission'] = $pShare;
            $sharingUsr = User::select('firstname', 'lastname', 'email')->find($subject->created_by);
        }
        $chapters = Chapter::where('partition_id', $subject->id)
            ->select(['name', 'perex', 'id', 'slug'])
            ->paginate(globalSettings::ITEMS_IN_PAGE);
        $pages = ceil(Chapter::where('partition_id', $subject->id)->count() / globalSettings::ITEMS_IN_PAGE);
        return ['chapters' => $chapters, 'subject' => $subject, 'pages' => $pages, 'sharingUsr' => $sharingUsr];
    }
    public function searchSubject(?String $search) {
        $subjects = auth()->user()->patritions()
            ->where('name', 'LIKE', '%' .$search .'%')
            ->get();
        if (count($subjects) === 0) {
            $subjects = ['not_found' => 'Nic nenalezeno!'];
        }
        return $subjects;
    }


}
