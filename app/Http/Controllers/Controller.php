<?php

namespace App\Http\Controllers;

use App\Http\Components\Localization;
use App\Models\Partition;
use App\Models\Permission;
use App\Models\Roles;
use App\Models\User;
use \App\Http\Components\Filter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /***
     * Sortování (provizorní)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sort(Request $request)
    {
        $sort = $request->input('sort', 'default');
        return Filter::sorting($sort);
    }

    /**
     * Slouží ke získání všech aktivních uživatelů v aplikaci
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUsersForSharing(Request $request)
    {
        $subject = Partition::where("slug", $request->slug)->first();
        $users = User::whereNotIn('id', [auth()->user()->id, Roles::ADMIN])
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
        foreach ($validated['users'] as $email) {
            $user = User::where('email', $email)->first();
            if ($user->patritions()->where("partition_id", $validated['subject'])->first() == null) {
                $user->patritions()->attach($validated['subject'], ['permission_id' => (int)$validated['permission'], 'accepted' => false]);
            } else {
                $sendMessage = __('share.warning.again_send');
            }
        }
        return redirect()->back()->with('message', $sendMessage);
    }

    /**
     * Zobrazení všech nabídek ke sdílení předmětu
     * @return \Inertia\Response
     */
    public function showShare()
    {
        $subjects = User::find(auth()->user()->id)
            ->patritions()
            ->where('accepted', false)
            ->with(['Users' => function ($query2) {
                $query2->select('email', 'firstname');
            }])->get();
        return Inertia::render('subjects/acceptSubject', compact('subjects'));
    }

    /**
     * Odmítnutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShare(Request $request)
    {
        $subject = Partition::where('slug', $request->slug)->first();
        $user = User::find(auth()->user()->id);
        $user->patritions()->detach($subject->id);
        return redirect()->back();
    }

    /**
     * Odstranění sdílení
     * @param Request $request
     * @return void
     */
    public function deleteShared($slug, $user) {
        $subject = Partition::where('slug', $slug)->first();
        $user = User::find($user);
        $user->patritions()->detach($subject->id);
        return redirect()->back();
    }

    /**
     * Přijmutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptShare(Request $request)
    {
        $subject = Partition::where('slug', $request->slug)->first();
        $user = User::find(auth()->user()->id);
        $user->patritions()->updateExistingPivot($subject->id, ['accepted' => 1]);
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
        $subjects = User::with(['patritions.users' => function ($query) {
             $query->whereNot('user_id', auth()->user()->id)->select('firstname', 'lastname', 'email', 'image')->get();
        }])->find(auth()->user()->id);
        $subjects->patritions->each(function ($subject) {
            $subject->users->each(function ($user) {
                $user->permission['name'] = Permission::where('id',$user->permission->permission_id)->pluck('permission')->first();
            });
        });
        $permission = Permission::all();
        return Inertia::render('subjects/sharedSubjects', ['subjects' => $subjects, 'permission' => $permission]);
    }

    /**
     * Úprava sdílení
     * @param Request $request
     * @return void
     */
    public function editShare(Request $request) {
        $user = User::where('email', $request->input('email'))->first();
        $dr = $request->input('permission');
        $subject = Partition::find($request->input('subject'));
        $user->patritions()->updateExistingPivot($subject->id,['permission_id' => $dr['id']]);
    }
    public function searchUser(Request $request) {
        $search = $request->input('select');
        $user = [];
        if(isset($search)) {
            $user = User::where('canShare' , 1)
                ->whereNotIn('id', [auth()->user()->id, Roles::ADMIN])
                ->where(function ($query) use ($search){
                $query->where('firstname', 'LIKE', '%'. $search . '%')
                    ->orWhere('lastname', 'LIKE', '%'. $search . '%')
                    ->orWhere('email', 'LIKE', '%'. $search . '%')->get();
            })->select('firstname', 'lastname', 'image', 'email')->get();
        }
        return response()->json($user);

    }
}
