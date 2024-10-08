<?php

namespace App\Http\Controllers;

use App\Actions\ExportAction;
use App\Enums\ToastifyStatus;
use App\Enums\UserRoles;
use App\Events\ChangeUserInformation;
use App\Exports\UsersExport;
use App\Http\Requests\AdminCreateUser;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Licences;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\settings;
use App\Models\User;
use App\Services\AdminService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class Admin extends Controller
{
    protected $userModel;
    private $service;

    public function __construct(User $userModel, AdminService $service)
    {
        $this->userModel = $userModel;
        $this->service = $service;
    }

    /**
     * ADMIN -Získání všech uživatelů
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'default');
        $data = $this->service->index($request->page, $sort, $request->url(), $request->query());
        return Inertia::render('Admin/ListUsers', $data);
    }
    public function sortIndex(Request $request) {
        $sort = $request->input('sort', 'default');
        return response()->json($this->service->index($request->page, $sort, $request->url(), $request->query()));
    }

    /**
     * ADMIN - Získáné informace o uživateli
     *
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($slug)
    {
        $usr = User::with(['roles', 'accountTypes', 'licences'])->where('slug', $slug)->firstOrFail();
        $this->authorize('view', $usr);
        if (auth()->user()->role_id === UserRoles::ADMIN) {
            $roles = Cache::rememberForever('roles', function () {
                return Roles::all();
            });
        } else {
            $roles = Roles::all()->whereNotIn('id', [1, 2])->values();
        }
        $accountTypes = Cache::rememberForever('accountTypes', function () {
            return AccountTypes::all();
        });
        $licences = Cache::rememberForever('licences', function () {
            return Licences::all();
        });

        return Inertia::render('Profile/Profile', compact(['usr', 'roles', 'accountTypes', 'licences']));
    }

    /**
     * ADMIN - Aktualizace uživatele
     *
     * @return \Illuminate\Http\RedirectResponse**
     */
    public function update(User $user, UpdateRequest $updateRequest)
    {
        $this->authorize('view', $user);
        User::find($user->id)->update([
            'firstname' => $updateRequest->firstname,
            'lastname' => $updateRequest->lastname,
            'type_id' => $updateRequest->type['id'],
            'role_id' => $user->role_id == UserRoles::ADMIN ? 1 : $updateRequest->role['id'],
            'active' => $updateRequest->active['id'],
            'licences_id' => $updateRequest->licences['id'],
        ]);
        event(new ChangeUserInformation($user));

        return redirect()->back()->with(['message' => __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * ADMIN - odkazázání na stránku, kde se dá vytvořit uživatel
     *
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('viewAny', auth()->user());
        $data = $this->service->create();

        return Inertia::render('Admin/CreateUser', $data);
    }

    /**
     * ADMIN - vytvoření uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminCreateUser $adminCreateUser)
    {
        $this->service->store($adminCreateUser);

        return to_route('adminusers')->with(['message' => 'Uživatel byl úspěšně vytvořen!', 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * ADMIN - Vymazání uživatele
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('view', $user);
        event(new ChangeUserInformation($user));
        $this->service->destroy($user);
        return redirect()->back()->with(['message' => __('validation.custom.deleted'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * ADMIN - Získání předmětů od uživatele v administraci
     *
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUserSubjects($slug)
    {
        $user = $this->userModel->getUserBySlug($slug);
        $this->authorize('view', $user);
        $subjects = $user->loadMissing('patritions');
        $subjects->patritions->each->append('chapter_count');

        return Inertia::render('Admin/ListSubjects', compact('subjects'));
    }

    /**
     * ADMIN - Odkázání na vytvoření předmětu u uživatele
     *
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createUserSubject($slug)
    {
        $user = $this->userModel->getUserBySlug($slug);
        $this->authorize('view', $user);
        $url = '/dashboard/admin/controll/' . $user->slug . '/subject/create';

        return Inertia::render('Subjects/CreateSubjects', compact('url'));
    }

    /**
     * ADMIN - vytvoření nového předmětu pod uživatelem
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUserSubject($slug, SubjectRequest $subjectRequest)
    {
        $user = $this->userModel->getUserBySlug($slug);
        $subject = $subjectRequest->only('name');
        $subject['icon'] = $subjectRequest->icon;
        $subject['created_by'] = $user->id;
        $subjectT = Partition::create($subject);

        $user->patritions()->attach($subjectT->id);

        return to_route('adminuser.Subjects', $user->slug);
    }

    /**
     * Povolí/zákáže registraci do aplikace
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeRestriction($register)
    {
        $this->authorize('viewAdmin', Auth()->user());
        $this->service->changeRestriction($register);

        return redirect()->back()->with(['message' => __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Nastaví barvu celé aplikace
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeTheme(Request $request, $color)
    {
        $this->authorize('viewAdmin', Auth()->user());
        Settings::find(1)->update(['color' => $color]);
        Cache::forget('color');

        return redirect()->back()->with(['message' => __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    public function userExportPDF(Request $request, ExportAction $action)
    {
        $extension = $request->input('export');
        $this->authorize('viewAdmin', Auth()->user());
        $class = new UsersExport();
        $sheet = $action->handle($extension, $class);

        return $sheet;
    }
}
