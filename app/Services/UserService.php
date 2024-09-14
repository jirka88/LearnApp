<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Http\Requests\BasicUserUpdateRequest;
use App\Models\AccountTypes;
use App\Models\Licences;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class UserService
{
    /**
     * Získá veškeré informace o uživateli a možné nastavení
     * @param User $user
     * @return array
     */
    public function getUserInfo(User $user): array
    {
        $user->loadMissing(['roles', 'accountTypes', 'licences']);
        $licences = [];
        $accountTypes = Cache::rememberForever('accountTypes', function () {
            return AccountTypes::all();
        });
        $language = app()->getLocale();
        if ($user->role_id == UserRoles::ADMIN) {
            $roles = Cache::rememberForever('roles_'.$language,function () use ($language) {
                return Roles::select('id', 'role_'.$language.' as role')->get();
            });
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } elseif ($user->role_id == UserRoles::OPERATOR) {
            $roles = Roles::whereNot('id', UserRoles::ADMIN)->get();
            $licences = Cache::rememberForever('licences', function () {
                return Licences::all();
            });
        } else {
            $roles = Roles::find(UserRoles::BASIC_USER)->get();
        }
        return ['usr' => $user, 'roles' => $roles, 'licences' => $licences, 'accountTypes' => $accountTypes];
    }

    /**
     * Aktualizuje uživatele
     * @return void
     */
    public function updateBasicInfo(User $user, User $actualUser, BasicUserUpdateRequest $updateRequest)
    {
        $typeAccount = $updateRequest->type['id'];
        if (in_array($actualUser->roles->id, [UserRoles::ADMIN, UserRoles::OPERATOR])) {
            $user->update([
                'firstname' => $updateRequest->firstname,
                'lastname' => $updateRequest->lastname,
                'type_id' => $typeAccount,
                'email' => $updateRequest->email,
            ]);
        } else {
            $user->update([
                'firstname' => $updateRequest->firstname,
                'lastname' => $updateRequest->lastname,
                'type_id' => $typeAccount,
            ]);
        }
        Cache::forget('userTypeAccount' . $user->id);
    }

    /**
     * Aktualizuje roli uživatele
     * @param User $user
     * @param mixed $role
     * @return void
     */
    public function updateRole(User $user, array $role)
    {
        $user->update([
            'role_id' => $role['id'],
        ]);
        Cache::forget('userRole' . $user->id);
    }
}
