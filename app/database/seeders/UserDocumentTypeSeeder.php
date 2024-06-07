<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserDocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = \App\Models\User::where('system_role', true)->first();

        $values = [
            [
                'owner' => $superAdmin->id,
                'name' => 'Carteira de trabalho',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Carteira funcional',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'CNH',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'CPF',
                'mask' => '999.999.999-99',
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Passaporte',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'RG',
                'mask' => '99.999.999-9',
                'validation' => null,
                'required' => true,
            ],
            [
                'owner' => $superAdmin->id,
                'name' => 'Título de eleitor',
                'mask' => null,
                'validation' => null,
                'required' => true,
            ],
        ];

        \App\Models\UserDocumentType::factory(count($values))
            ->state(new Sequence(...$values))
            ->create();
    }
}
