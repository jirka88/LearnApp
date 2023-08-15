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
    public function showUsersForSharing() {
        $users = User::where("active", true)->get(['email', 'firstname']);
        return response()->json($users);
    }

    public function share(Request $request) {
        $customMessages = [
            'users.required' => 'Je nutné vyplnit uživatele.',
        ];
        $validated = $request->validate([
            'users' => 'required',
        ], $customMessages);


    }
}
