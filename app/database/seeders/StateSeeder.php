<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = file_get_contents(storage_path('app') . '/states.sql');
        DB::insert($states);
    }
}
