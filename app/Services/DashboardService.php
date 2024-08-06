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
    public function index($userModel, int $user_role_id): ?array
    {
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
}
