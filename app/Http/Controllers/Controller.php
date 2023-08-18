<?php

namespace App\Http\Controllers;

use App\Models\Partition;
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
    //TO DO
    public function showUsersForSharing() {
        /*dd(Partition::with('Users')->whereHas('Users', function ($query) {
            $query->where('user_id', )
        }));*/
        $users = User::where("active", true)->whereNot('id', auth()->user()->id)->get(['email', 'firstname']);
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
     * Zorbrazení všech nabídek ke sdílení předmětu
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

}
