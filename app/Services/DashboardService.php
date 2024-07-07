<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Models\AccountTypes;
use App\Models\Chapter;
use App\Models\Licences;
use App\Models\Roles;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Získání informace u dashboardu
     * @return array|null
     */
    public function index($userModel, int $user_role_id) {
        if ($user_role_id == UserRoles::ADMIN) {
            $chapterCount = Cache::rememberForever('chapterAllCount', function () {
                return Chapter::all()->count();
            });
            $stats = ([
                'users' => Cache::rememberForever('userCount', function () use ($userModel) {
                    return $userModel->append('get_count_users');
                }),
                'operators' => $userModel->getUserCountByRole(UserRoles::OPERATOR),
                'normalUsers' => $userModel->getUserCountByRole(UserRoles::BASIC_USER),
                'chapters' => $chapterCount,
                'testersCount' => $userModel->getUserCountByRole(UserRoles::TESTER),
                'restrictRegister' => Cache::rememberForever('restrictRegister', function () {
                    return Settings::pluck('RestrictedRegistration')->first();
                })]);
        } else {
            $stats = null;
        }
        return $stats;
    }

    /**
     * Získá veškeré informace o uživateli a možné nastavení
     * @param User $user
     * @return array
     */
    public function getUserInfo(User $user) {
        $usr = $user->loadMissing(['roles', 'accountTypes', 'licences']);
        $roles = [];
        $licences = [];
        $accountTypes = Cache::rememberForever('accountTypes', function () {
            return AccountTypes::all();
        });
        if ($user->role_id == UserRoles::ADMIN) {
            $roles = Cache::rememberForever('roles', function () {
                return Roles::all();
            });
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } elseif ($user->role_id  == UserRoles::OPERATOR) {
            $roles = Roles::whereNot('id', UserRoles::ADMIN)->get();
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } else {
            $roles = Roles::find(UserRoles::BASIC_USER)->get();
        }
        return ['usr' => $user, 'roles' => $roles, 'licences' => $licences, 'accountTypes' => $accountTypes];
    }
}
