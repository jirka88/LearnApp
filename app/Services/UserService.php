<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Http\Requests\UpdateRequest;
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

    /**
     * Aktualizuje uživatele
     * @return void
     */
    public function update(User $user, UpdateRequest $updateRequest) {
        $typeAccount = $updateRequest->type['id'];
        $user->update([
            'firstname' => $updateRequest->firstname,
            'lastname' => $updateRequest->lastname,
            'type_id' => $typeAccount,
        ]);
        Cache::forget('userTypeAccount' . $user->id);
    }
}
