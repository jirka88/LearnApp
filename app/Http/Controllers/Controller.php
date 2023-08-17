<?php

namespace App\Http\Controllers;

use App\Models\Partition;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
            $user->patritions()->attach($validated['subject'], ['owner' => false, 'permission_id' => (int)$validated['permission'], 'accepted' => false]);
        }
        return redirect()->back()->with('successUpdate', 'Žádost o sdílení byla zaslána!');
    }
}
