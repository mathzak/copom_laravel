<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 16; $i++) {
            $cities = file_get_contents(storage_path('app') . "/cities$i.sql");
            DB::insert($cities);
        }
    }
}
