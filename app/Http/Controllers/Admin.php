<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Components\Filters;
use App\Http\Components\globalSettings;
use App\Http\Requests\AdminCreateUser;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\AccountTypes;
use App\Models\Chapter;
use App\Models\Licences;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\settings;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Admin extends Controller
{
    /**
     * ADMIN -Získání všech uživatelů
     * @return \Inertia\Response
     */
    public function index()
    {
        $users = User::orderBy('role_id', 'ASC')->orderby('id', 'ASC')->with(['roles', 'licences'])->paginate(globalSettings::ITEMS_IN_PAGE);
        $pages = ceil(count(User::all()) / globalSettings::ITEMS_IN_PAGE);
        return Inertia::render('admin/listUsers', ['users' => $users, 'pages' => $pages]);
    }

    /**
     * ADMIN - Získáné informace o uživateli
     * @param $slug
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($slug)
    {
        $usr = User::with(['roles', 'accountTypes', 'licences'])->where('slug', $slug)->first();
        $this->authorize('view', $usr);
        $isAdmin = auth()->user()->role_id == 1 ? true : false;
        if ($isAdmin) {
            $roles = Roles::all();
        } else {
            $roles = Roles::all()->whereNotIn('id', [1, 2])->values();
        }
        $accountTypes = AccountTypes::all();
        $licences = Licences::all();
        return Inertia::render('user/user', compact(['usr', 'roles', 'accountTypes', 'licences']));
    }

    /**
     * ADMIN - Aktualizace uživatele
     * @param User $user
     * @param UpdateRequest $updateRequest
     * @return \Illuminate\Http\RedirectResponse**
     */
    public function update(User $user, UpdateRequest $updateRequest)
    {
        $role = 0;
        if (UserRoles::ADMIN == $user->role_id) {
            $role = 1;
        } else {
            $role = $updateRequest->role['id'];
        }
        $typeAccount = $updateRequest->type['id'];
        $active = $updateRequest->active['id'];
        $licence = $updateRequest->licences['id'];
        $this->authorize('view', $user);
        User::find($user->id)->update([
            'firstname' => $updateRequest->firstname,
            'lastname' => $updateRequest->lastname,
            'type_id' => $typeAccount,
            'role_id' => $role,
            'active' => $active,
            'licences_id' => $licence
        ]);
        return redirect()->back()->with('message', __('validation.custom.update'));
    }

    /**
     * ADMIN - odkazázání na stránku, kde se dá vytvořit uživatel
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('viewAny', auth()->user());
        $accountTypes = AccountTypes::all();
        $licences = Licences::all();
        if (auth()->user()->role_id == 1) {
            $roles = Roles::all();
        } else {
            $roles = Roles::all()->whereNotIn("id", [1, 2])->values();
        }
        return Inertia::render('admin/createUser', compact('accountTypes', 'roles', 'licences'));
    }

    /**
     * ADMIN - vytvoření uživatele
     * @param AdminCreateUser $adminCreateUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminCreateUser $adminCreateUser)
    {
        User::create([
            "firstname" => $adminCreateUser->firstname,
            "lastname" => $adminCreateUser->lastname,
            "email" => $adminCreateUser->email,
            "password" => $adminCreateUser->password,
            "role_id" => $adminCreateUser->role["id"],
            "type_id" => $adminCreateUser->type["id"],
            "licences_id" => $adminCreateUser->licence["id"],
            "slug" => SlugService::createSlug(User::class, 'slug', $adminCreateUser->firstname)
        ]);
        return to_route('admin')->with('message', 'Uživatel byl úspěšně vytvořen!');
    }

    /**
     * ADMIN - Vymazání uživatele
     * @param User $user
     * @return void
     */
    public function destroy(User $user)
    {
        $this->authorize('view', $user);
        $user->patritions()->detach();
        User::destroy($user->id);
    }

    /**
     * ADMIN - Získání předmětů od uživatele v administraci
     * @param $slug
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getUserSubjects($slug)
    {
        $user = app('App\Models\User');
        $user = $user->getUserBySlug($slug);
        $this->authorize('view', $user);
        $subjects = User::with(['patritions' => function ($query) {
            $query->withCount('chapter');
        }])->find($user->id);
        return Inertia::render('admin/listSubjects', compact('subjects'));
    }

    /**
     * ADMIN - Odkázání na vytvoření předmětu u uživatele
     * @param $slug
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createUserSubject($slug)
    {
        $user = app('App\Models\User');
        $user = $user->getUserBySlug($slug);
        $this->authorize('view', $user);
        $url = "/dashboard/admin/controll/" . $user->slug . "/subject/create";
        return Inertia::render('subjects/createSubjects', compact('url'));
    }

    /**
     * ADMIN - vytvoření nového předmětu pod uživatelem
     * @param $slug
     * @param SubjectRequest $subjectRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUserSubject($slug, SubjectRequest $subjectRequest)
    {
        $user = app('App\Models\User');
        $user = $user->getUserBySlug($slug);
        $subject = $subjectRequest->only("name");
        $subject["icon"] = $subjectRequest->icon;
        $subject["created_by"] = $user->id;
        $subjectT = Partition::create($subject);

        $user->patritions()->attach($subjectT->id);
        return to_route('adminuser.subjects', $user->slug);
    }

    public function getStats()
    {
        if(auth()->user()->role_id == 1) {
            $userCount = User::all()->count();
            $operatosCount = User::where('role_id', UserRoles::OPERATOR)->get()->count();
            $userNormalCount = User::where('role_id', UserRoles::BASIC_USER)->get()->count();
            $testersCount = User::where('role_id', UserRoles::TESTER)->get()->count();
            $allChapters = Chapter::all()->count();
            $restrictRegister = Settings::all()->pluck('RestrictedRegistration')->first();
            $stats = ([
                'users' =>  $userCount,
                'operators' => $operatosCount,
                'normalUsers' => $userNormalCount,
                'chapters' => $allChapters,
                'testersCount' => $testersCount,
                'restrictRegister' => $restrictRegister]);
        }
        else {
            $stats = null;
        }
        return $stats;
    }

    /**
     * Povolí/zákáže registraci do aplikace
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeRestriction($register) {
        $value = filter_var($register,FILTER_VALIDATE_BOOLEAN);
        $this->authorize('viewAdmin', auth()->user());
        Settings::find(1)->update(['RestrictedRegistration' => $value]);
        return redirect()->back()->with('message', __('validation.custom.update'));
    }

    /**
     * Nastaví barvu celé aplikace
     * @param $color
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeTheme($color) {
        $this->authorize('viewAdmin', auth()->user());
        Settings::find(1)->update(['color' => $color]);
        return redirect()->back()->with('message', __('validation.custom.update'));
    }
}
