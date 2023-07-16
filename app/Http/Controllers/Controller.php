<?php

namespace App\Http\Controllers;

use App\Models\Partition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function sort(Request $request) {
        $sort = $request->input('sort', 'default');
        if($sort !== 'default') {
            $subjects = Partition::orderBy('name',$sort)->where('created_by', auth()->user()->id)->get();
            return response()->json($subjects);
            //return redirect()->route('subject.index',['sort'=>$sort]);
        }
        else {
            $subjects = Partition::all()->where('created_by', auth()->user()->id);
            //return redirect()->route('subject.index',['sort'=>$sort]);
            return response()->json($subjects);
        }
    }
}
