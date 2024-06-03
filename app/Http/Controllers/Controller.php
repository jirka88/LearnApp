<?php

namespace App\Http\Controllers;

use App\Actions\ChangeLanguageAction;
use App\Enums\UserRoles;
use App\Http\Resources\UserSelectResource;
use App\Models\Partition;
use App\Models\User;
use App\Services\SubjectService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /***
     * Sortování
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request, Partition $partition) {
        $sort = $request->input('sort', 'default');
        return response()->json(['search' => $partition->sortSubjects($sort, $request->page, $request->url(), $request->query())]);
    }

    /**
     * Slouží ke získání všech aktivních uživatelů v aplikaci
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUsersForSharing(Request $request) {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request->slug);
        $users = User::whereNotIn('id', [auth()->user()->id, UserRoles::ADMIN])
            ->where('canShare', true)
            ->whereDoesntHave('patritions', function ($query) use ($subject) {
                $query->where('partition_id', $subject->id);
            })->get();

        return response()->json($users);
    }

    public function changeLanguage($language, ChangeLanguageAction $action) {
        $action->handle($language);
        return Redirect()->back();
    }

    /**
     * Vyhledání uživatele
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUser(Request $request) {
        $search = $request->input('select');
        $user = [];
        if (isset($search)) {
            $user = UserSelectResource::Collection(User::where('canShare', 1)
                ->whereNotIn('id', [auth()->user()->id, UserRoles::ADMIN])
                ->whereNot('email_verified_at', null)
                ->where(function ($query) use ($search) {
                    $query->where('firstname', 'LIKE', '%'.$search.'%')
                        ->orWhere('lastname', 'LIKE', '%'.$search.'%')
                        ->orWhere('email', 'LIKE', '%'.$search.'%')->get();
                })->get());
        }

        return response()->json($user);

    }
}
