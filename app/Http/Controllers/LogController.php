<?php

namespace App\Http\Controllers;

use App\Actions\ExportAction;
use App\Enums\ToastifyStatus;
use App\Exports\LogExport;
use App\Http\Components\globalSettings;
use App\Http\Resources\LogResource;
use App\Http\Resources\UserSelectResource;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    private $service;
    public function __construct(LogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request) {
        $this->authorize('viewAny', Auth()->user());
        $data = $this->service->index();
        return Inertia::render('Log', $data);
    }
    public function show(Request $request, Activity $activity)
    {
        $this->authorize('viewAny', Auth()->user());
        $activity = $this->service->show($activity);
        return Inertia::render('Admin/LogShow', ['activity' => $activity]);
    }
    public function destroy(Request $request, Activity $activity)
    {
        $activity->delete();
        return redirect()->back()->with(['message' => __('validation.custom.deleted'), 'status' => ToastifyStatus::SUCCESS]);
    }
    public function exportFile(Request $request, ExportAction $action) {
        $sheet = $this->service->exportFile($request->input('export'), $action);
        return $sheet;

    }
}
