<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        $values = [
            [
                'name' => '::SUPERADMIN::',
                'description' => 'Superadmin role',
                'inalterable' => true,
                'superadmin' => true,
                'manager' => true,
                'active' => true,
            ],
            [
                'name' => '::MANAGER::',
                'description' => 'Manager role',
                'inalterable' => true,
                'superadmin' => false,
                'manager' => true,
                'active' => true,
            ],
        ];

        \App\Models\Role::factory(count($values))
            ->state(new Sequence(...$values))
            ->create();
    }
}
