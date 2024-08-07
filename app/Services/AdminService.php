<?php

namespace App\Services;

use App\Enums\ToastifyStatus;
use App\Enums\UserRoles;
use App\Events\ChangeUserInformation;
use App\Http\Components\globalSettings;
use App\Models\AccountTypes;
use App\Models\Licences;
use App\Models\Roles;
use App\Models\Settings;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class AdminService
{
    public function index(?int $page, ?String $sort, ?String $url, ?array $query) {
        if ($sort && strtolower($sort) !== 'default') {
            $data = User::filter()
                ->with(['roles', 'licences'])
                ->where('user_active', 1)
                ->paginate(globalSettings::ITEMS_IN_PAGE);
            $users = new LengthAwarePaginator($data, count($data), globalSettings::ITEMS_IN_PAGE, $page, [
                'path' => $url,
                'query' => $query
            ]);
        }
        else {
            $data = User::orderBy('role_id', 'ASC')
                ->orderBy('id', 'ASC')
                ->where('user_active', 1)
                ->with(['roles', 'licences'])
                ->paginate(globalSettings::ITEMS_IN_PAGE);
            $users = new LengthAwarePaginator($data, count($data), globalSettings::ITEMS_IN_PAGE, $page, [
                'path' => $url,
                'query' => $query
            ]);
        }
        $pages = ceil(User::all()->count() / globalSettings::ITEMS_IN_PAGE);
        return ['users' => $users, 'pages' => $pages, 'sort' => $sort];
    }

    /**
     * Vrátí globální informace --> typy účtů, licence, role - podle oprávnění
     * @return array
     */
    public function create() {
        $accountTypes = Cache::rememberForever('accountTypes', function () {
            return AccountTypes::all();
        });
        $licences = Cache::rememberForever('licences', function () {
            return Licences::all();
        });
        if (auth()->user()->role_id == UserRoles::ADMIN) {
            $roles = Cache::rememberForever('roles', function () {
                return Roles::all();
            });
        } else {
            $roles = Roles::all()->whereNotIn('id', [1, 2])->values();
        }
        return ['accountTypes' => $accountTypes, 'licences' => $licences, 'roles' => $roles];
    }

    /**
     * Vytvoření uživatele
     * @param $user
     * @return void
     */
    public function store($user) {
        $createdUser = User::create([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'password' => $user->password,
            'role_id' => $user->role['id'],
            'type_id' => $user->type['id'],
            'licences_id' => $user->licence['id'],
            'slug' => SlugService::createSlug(User::class, 'slug', $user->firstname),
        ]);
        Activity()->event('created')->causedBy(auth()->user())->performedOn($createdUser)->log('Vytvoření uživatele');
    }

    /**
     * Vymazání vytvořených předmětů a deaktivace uživatele
     * @param $user
     * @return void
     */
    public function destroy($user) {
        $user->patritions()->detach();
        $user->update(['user_active' => 0, 'active' => 0]);
        Activity()->event('deleted')->causedBy(auth()->user())->performedOn($user)->log('Vymazání uživatele');
        event(new ChangeUserInformation($user));
        Cache::forget('Subjects' . $user->id);
        Cache::forget('userCount');
    }

    public function changeRestriction(String $register)
    {
        Settings::find(1)->update(['RestrictedRegistration' => filter_var($register, FILTER_VALIDATE_BOOLEAN)]);
        Cache::forget('restrictRegister');
    }

}
