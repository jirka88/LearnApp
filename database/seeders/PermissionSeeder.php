<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permission = [
            ['permission' => 'read'],
            ['permission' => 'read&write'],
            ['permission' => 'fullControl'],
        ];
        foreach ($permission as $permiss) {
            Permission::create($permiss);
        }

    }
}
