<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Enums\UserRoles;
use App\Http\Components\FilterSubjectSort;
use App\Http\Components\Localization;
use App\Http\Resources\UserSelectResource;
use App\Models\Partition;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /***
     * Sortování
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request) {
        $sort = $request->input('sort', 'default');
        $filter = new FilterSubjectSort;

        return response()->json(['search' => $filter->sorting($sort)]);
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

    /**
     * Odstranění sdílení
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShared($slug, $user) {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($slug);
        $user = User::find($user);
        $user->patritions()->detach($subject->id);
        Cache::forget('sharedSubjects');

        return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo smazáno']);
    }

    public function changeLanguage(Request $request, $language) {
        if (in_array($language, Localization::$supportedLanguages)) {
            Localization::setLocale($language);
        }

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
                ->where(function ($query) use ($search) {
                    $query->where('firstname', 'LIKE', '%'.$search.'%')
                        ->orWhere('lastname', 'LIKE', '%'.$search.'%')
                        ->orWhere('email', 'LIKE', '%'.$search.'%')->get();
                })->get());
        }

        return response()->json($user);

    }
}
