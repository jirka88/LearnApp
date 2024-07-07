<?php

namespace App\Services;

use App\Exports\LogExport;
use App\Http\Components\globalSettings;
use App\Http\Resources\LogResource;
use App\Http\Resources\UserSelectResource;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class LogService
{
    public function index()
    {
        $data = LogResource::collection(Activity::orderBy('created_at', 'DESC')->paginate(globalSettings::ITEMS_IN_PAGE));
        $pages = ceil(count($data) / globalSettings::ITEMS_IN_PAGE);

        foreach ($data as $activity) {
            $activity->causer = $activity->causer;
            $activity->created_at = Carbon::parse($activity->created_at)->format('d.m.Y H:i:s');
        }
        return ['data' => $data, 'pages' => $pages];
    }
    public function show(Activity $activity)
    {
        $activity->created_at = Carbon::parse($activity->created_at)->format('d.m.Y H:i:s');
        new UserSelectResource($activity->causer->loadMissing(['roles']));
        if ($activity->properties->isEmpty() && $activity->subject) {
            $activity->subject->loadMissing(['roles', 'accountTypes', 'licences']);
        }
        return $activity;
    }
    public function exportFile($exportInput, $action) {
        $extension = $exportInput;
        $class = new LogExport();
        $sheet = $action->handle($extension, $class);
        return $sheet;
    }
}
