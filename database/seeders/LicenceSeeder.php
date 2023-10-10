<?php

namespace Database\Seeders;

use App\Models\Licences;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $licences = [
            ["Licence" => "Standart"],
            ["Licence" => "+Standart"],
            ["Licence" => "Premium"]
        ];
        foreach($licences as $licence) {
            Licences::create($licence);
        }
    }
}
