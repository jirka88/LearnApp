<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $roles = [
            ['role_cs' => 'Správce', 'role_en' => 'Administrator'],
            ['role_cs' => 'Operátor', 'role_en' => 'Operator'],
            ['role_cs' => 'Tester', 'role_en' => 'Tester'],
            ['role_cs' => 'Uživatel', 'role_en' => 'User'],
        ];

        foreach ($roles as $role) {
            Roles::create($role);
        }
    }
}
