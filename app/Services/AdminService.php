<?php

namespace App\Services;

use App\Http\Components\globalSettings;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AdminService
{
    public function index() :array {
        $users = User::orderBy('role_id', 'ASC')
            ->orderBy('id', 'ASC')
            ->with(['roles', 'licences'])
            ->paginate(globalSettings::ITEMS_IN_PAGE);
        $pages = ceil(User::all()->count() / globalSettings::ITEMS_IN_PAGE);
        return ['users' => $users, 'pages' => $pages];
    }
    public function store($user) {
        User::create([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'password' => $user->password,
            'role_id' => $user->role['id'],
            'type_id' => $user->type['id'],
            'licences_id' => $user->licence['id'],
            'slug' => SlugService::createSlug(User::class, 'slug', $user->firstname),
        ]);
    }
}
