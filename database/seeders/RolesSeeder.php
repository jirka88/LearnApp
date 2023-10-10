<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['role' => 'Správce'],
            ['role' => 'Operátor'],
            ['role' => 'Tester'],
            ['role' => 'Uživatel']
        ];

        foreach ($roles as $role) {
        Roles::create($role);
    }
    }
}
