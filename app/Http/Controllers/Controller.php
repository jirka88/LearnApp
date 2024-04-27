<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Enums\UserRoles;
use App\Http\Components\FilterSubjectSort;
use App\Http\Components\Localization;
use App\Http\Resources\UserSelectResource;
use App\Models\Partition;
use App\Models\Permission;
use App\Models\Roles;
use App\Models\User;
use \App\Http\Components\Filters;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /***
     * Sortování
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request)
    {
        $sort = $request->input('sort', 'default');
        $filter = new FilterSubjectSort();
        return response()->json(["search" => $filter->sorting($sort)]);
    }

    /**
     * Slouží ke získání všech aktivních uživatelů v aplikaci
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUsersForSharing(Request $request)
    {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request->slug);
        $users = User::whereNotIn('id', [auth()->user()->id, UserRoles::ADMIN])
            ->where('canShare', true)
            ->whereDoesntHave('patritions', function ($query) use ($subject) {
                $query->where("partition_id", $subject->id);
            })->get();
        return response()->json($users);
    }

    /**
     * Slouží ke vytvoření sdílení
     * @param Request $request
     * @return void
     */
    public function share(Request $request)
    {
        $customMessages = [
            'users.required' => __('share.warning.required_user'),
            'permission.required' => __('share.warning.required_permission')
        ];
        $validated = $request->validate([
            'users' => 'required',
            'permission' => 'required',
            'subject' => 'required'
        ], $customMessages);
        $sendMessage = __('share.warning.send');
        $status = ToastifyStatus::SUCCESS;
        foreach ($validated['users'] as $email) {
            $user = User::where('email', $email)->first();
            if ($user->patritions()->where("partition_id", $validated['subject'])->first() == null) {
                $user->patritions()->attach($validated['subject'], ['permission_id' => (int)$validated['permission'], 'accepted' => false]);
            } else {
                $sendMessage = __('share.warning.again_send');
                $status = ToastifyStatus::INFO;
            }
        }
        Cache::forget("sharedSubjects");
        return redirect()->back()->with(['message' => $sendMessage, "status" => $status]);
    }

    /**
     * Zobrazení všech nabídek ke sdílení předmětu
     * @return \Inertia\Response
     */
    public function showShare()
    {
        $subjects = auth()->user()
            ->patritions()
            ->where('accepted', false)
            ->with(['Users' => function ($query2) {
                UserSelectResource::make($query2->where('permission_id', null))->first();}])->get();
        return Inertia::render('subjects/acceptSubject', compact('subjects'));
    }

    /**
     * Odmítnutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShare(Request $request)
    {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request->slug);
        auth()->user()->patritions()->detach($subject->id);
        return redirect()->back();
    }

    /**
     * Odstranění sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShared($slug, $user) {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($slug);
        $user = User::find($user);
        $user->patritions()->detach($subject->id);
        Cache::forget("sharedSubjects");
        return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Sdílení bylo smazáno']);
    }

    /**
     * Přijmutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptShare(Request $request)
    {
        $subjecModel = app('\App\Models\Partition');
        $subject = $subjecModel->getSubjectBySlug($request->slug);
        auth()->user()->patritions()->updateExistingPivot($subject->id, ['accepted' => 1]);
        Cache::forget("sharedSubjects");
        return redirect()->back();
    }

    public function changeLanguage(Request $request, $language)
    {
        if (in_array($language, Localization::$supportedLanguages)) {
            Localization::setLocale($language);
        }
        return Redirect()->back();
    }

    /**
     * Zobrazí pod uživateleme všechny jeho sdílení
     * @return \Inertia\Response
     */
    public function showStatsShare() {
        $user = auth()->user();
        $partitions = $user->patritions()->where('permission_id', null)
            ->with(['users' => function ($query) {
            UserSelectResource::make($query->whereNot('user_id', auth()->user()->id)->get());
        }])->get();
        $partitions->each(function ($subject) {
            $subject->users->each(function ($user) {
                $user->permission['name'] = Permission::where('id',$user->permission->permission_id)->pluck('permission')->first();
            });
        });
        $subjects = $partitions->filter(function ($patrition) {
            return $patrition->users->isNotEmpty();
        });
        $permission = Cache::rememberForever('permission', function() {
            return Permission::all();
        });
        return Inertia::render('subjects/sharedSubjects', ['subjects' => $subjects, 'permissions' => $permission]);
    }

    /**
     * Úprava sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editShare(Request $request) {
        $userModel = app('\App\Models\User');
        $user = $userModel->getUserByEmail($request->input('email'));
        $dr = $request->input('permission');
        $subject = Partition::find($request->input('subject'));
        $user->patritions()->updateExistingPivot($subject->id,['permission_id' => $dr['id']]);
        return redirect()->back()->with(['status' => ToastifyStatus::SUCCESS, 'message' => __('validation.custom.update')]);
    }

    /**
     * Vyhledání uživatele
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUser(Request $request) {
        $search = $request->input('select');
        $user = [];
        if(isset($search)) {
            $user = UserSelectResource::Collection(User::where('canShare' , 1)
                ->whereNotIn('id', [auth()->user()->id, UserRoles::ADMIN])
                ->where(function ($query) use ($search){
                $query->where('firstname', 'LIKE', '%'. $search . '%')
                    ->orWhere('lastname', 'LIKE', '%'. $search . '%')
                    ->orWhere('email', 'LIKE', '%'. $search . '%')->get();
            })->get());
        }
        return response()->json($user);

    }
}
