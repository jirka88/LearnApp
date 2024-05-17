<?php

namespace Database\Seeders;

use App\Models\AccountTypes;
use Illuminate\Database\Seeder;

class AccountTypesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $types = [
            ['type' => 'Osobní'],
            ['type' => 'Školní'],
        ];

        foreach ($types as $type) {
            AccountTypes::create($type);
        }
    }
}
