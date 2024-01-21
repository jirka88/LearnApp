<?php
namespace App\Http\Components;
use App\Models\Partition;
use Illuminate\Http\JsonResponse;

class Filter {
    public static function sorting($sort) : JsonResponse {
        if ($sort !== 'default') {
            $subjects = Partition::orderBy('name', $sort)->where('created_by', auth()->user()->id)->paginate(20);
        } else {
            $subjects = Partition::where('created_by', auth()->user()->id)->paginate(20);
        }
        return response()->json($subjects);
    }
}
