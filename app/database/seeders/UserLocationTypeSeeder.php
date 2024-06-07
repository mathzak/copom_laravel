<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserLocationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = \App\Models\User::where('system_role', true)->first();

        $values = [
            [
                'owner' => $superAdmin->id,
                'name' => 'Empresarial (filial)',
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Empresarial (sede)',
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Residêncial (contato)',
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Residêncial (próprio)',
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Trabalho',
                'required' => true,
            ],
        ];

        \App\Models\UserLocationType::factory(count($values))
            ->state(new Sequence(...$values))
            ->create();
    }
}
