<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['firstname' => 'Jirka',
                'lastname' => 'Navrátil',
                'role_id' => 1,
                'email' => 'navratil.jiri@atlas.cz',
                'type_id' => 1,
                'password' => 'Aa123456#',
                'slug' => 'jirka'],
            ['firstname' => 'Test',
                'lastname' => 'Převrátil',
                'role_id' => 2,
                'email' => 'laval@centrum.cz',
                'type_id' => 1,
                'password' => 'Aa123456#',
                'slug' => 'test'],
            ['firstname' => 'Test2',
                'lastname' => 'Odvrátil',
                'role_id' => 4,
                'email' => 'test@test.cz',
                'type_id' => 1,
                'password' => 'Aa123456#',
                'slug' => 'test2']
        ];
        foreach ($users as $user) {
            User::create($user);
        }

    }
}
