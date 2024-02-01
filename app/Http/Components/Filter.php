<?php
namespace App\Http\Components;
use App\Models\Partition;
use Illuminate\Http\JsonResponse;

class Filter {
    public const DEFAULT_VALUE = 'default';
    public static function sorting($sort) : JsonResponse {
        if ($sort !== 'default') {
            $subjects = Partition::withCount('Chapter')->orderBy('name', $sort)->where('created_by', auth()->user()->id)->paginate(20);
        } else {
            $subjects = Partition::withCount('Chapter')->where('created_by', auth()->user()->id)->paginate(20);
        }
        return response()->json($subjects);
    }
}
