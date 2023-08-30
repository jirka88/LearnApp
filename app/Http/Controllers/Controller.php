<?php

namespace App\Http\Controllers;

use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
    public function sort(Request $request) {
        $sort = $request->input('sort', 'default');
        if($sort !== 'default') {
            $subjects = Partition::orderBy('name',$sort)->where('created_by', auth()->user()->id)->paginate(20);
            return response()->json($subjects);
        }
        else {
            $subjects = Partition::where('created_by', auth()->user()->id)->paginate(20);
            return response()->json($subjects);
        }
    }

    /**
     * Slouží ke získání všech aktivních uživatelů v aplikaci
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUsersForSharing(Request $request) {
        $users = User::with('patritions')->where(function ($query) {
            $query->where("active", true);
            $query->whereNot('id', auth()->user()->id);
            $query->whereNot('id', Roles::ADMIN);
            $query->where('canShare', true);
        })->get();
        return response()->json($users);
    }

    /**
     * Slouží ke vytvoření sdílení
     * @param Request $request
     * @return void
     */
    public function share(Request $request) {
        $customMessages = [
            'users.required' => 'Je nutné vyplnit uživatele.',
            'permission.required' => 'Musíte vyplnit oprávnění'
        ];
        $validated = $request->validate([
            'users' => 'required',
            'permission' => 'required',
            'subject' => 'required'
        ], $customMessages);
        foreach($validated['users'] as $email) {
            $user = User::where('email', $email)->first();
            $user->patritions()->attach($validated['subject'], ['permission_id' => (int)$validated['permission'], 'accepted' => false]);
        }
        return redirect()->back()->with('successUpdate', 'Žádost o sdílení byla zaslána!');
    }

    /**
     * Zobrazení všech nabídek ke sdílení předmětu
     * @return \Inertia\Response
     */
    public function showShare() {
        $subjects = User::with(['patritions' => function ($query) {
           $query->where('accepted', false);
           $query->with(['Users' => function ($query2) {
               $query2->select('email', 'firstname');
           }]);
        }])->find(auth()->user()->id);
        return Inertia::render('subjects/acceptSubject', compact('subjects'));
    }

    /**
     * Odmítnutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShare(Request $request) {
        $subject = Partition::where('slug', $request->slug)->first();
        $user = User::find(auth()->user()->id);
        $user->patritions()->detach($subject->id);
        return redirect()->back();
    }

    /**
     * Přijmutí sdílení
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptShare(Request $request) {
        $subject = Partition::where('slug', $request->slug)->first();
        $user = User::find(auth()->user()->id);
        $user->patritions()->updateExistingPivot($subject->id, ['accepted' => 1]);
        return redirect()->back();
    }

}
