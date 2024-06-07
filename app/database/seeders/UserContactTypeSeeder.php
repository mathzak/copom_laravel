<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserContactTypeSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = \App\Models\User::where('system_role', true)->first();

        $values = [
            [
                'owner' => $superAdmin->id,
                'name' => 'Email',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Telefone celular',
                'mask' => '(99) 99999-9999',
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Telefone fixo',
                'mask' => '(99) 9999-9999',
                'validation' => null,
                'required' => true,
            ],
        ];

        \App\Models\UserContactType::factory(count($values))
            ->state(new Sequence(...$values))
            ->create();
    }
}
