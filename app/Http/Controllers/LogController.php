<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Http\Components\globalSettings;
use App\Http\Resources\UserSelectResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(Request $request) {
        $this->authorize('viewAdmin', Auth()->user());
        $data = Activity::orderBy('created_at', 'DESC')->paginate(globalSettings::ITEMS_IN_PAGE);
        $pages = ceil(count($data) / globalSettings::ITEMS_IN_PAGE);

        foreach ($data as $activity) {
            $activity->causer = $activity->causer;
            $activity->created_at = Carbon::parse($activity->created_at)->format('d.m.Y H:i:s');
        }
        return Inertia::render('Log', ['data' => $data, 'pages' => $pages]);
    }
    public function show(Request $request, Activity $activity)
    {
        $this->authorize('viewAdmin', Auth()->user());
        new UserSelectResource($activity->causer->loadMissing(['roles']));
        return Inertia::render('admin/logShow', ['activity' => $activity]);
    }
    public function destroy(Request $request, Activity $activity)
    {
        $activity->delete();
        return redirect()->back()->with(['message' => __('validation.custom.deleted'), 'status' => ToastifyStatus::SUCCESS]);
    }
}
