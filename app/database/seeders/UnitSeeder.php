<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = \App\Models\User::where('system_role', true)->first();
        $cities = \App\Models\City::where('state_id', 2022)->get();

        $city = $cities->firstWhere('id', 11524);

        \App\Models\Unit::factory()->create([
            'owner' => $superAdmin->id,
            'name' => 'PMPR',
            'nickname' => 'PMPR',
            'parent_id' => 0,
            'country_id' => $city->country_id,
            'country_code' => $city->country_code,
            'state_id' => $city->state_id,
            'state_code' => $city->state_code,
            'city_id' => $city->id,
            'city_name' => $city->name,
        ]);

        \App\Models\Unit::factory(6)
            ->sequence(
                function (Sequence $sequence) use ($superAdmin, $cities) {
                    $city = $cities->random();

                    return [
                        'owner' => $superAdmin->id,
                        'name' => ($sequence->index + 1) . ' CRPM',
                        'nickname' => ($sequence->index + 1) . ' CRPM',
                        'parent_id' => 1,
                        'order' => $sequence->index + 1,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ];
                }
            )->create();

        $city = $cities->random();

        \App\Models\Unit::factory(30)
            ->sequence(function (Sequence $sequence) use ($superAdmin, $cities) {
                $city = $cities->random();

                return [
                    'owner' => $superAdmin->id,
                    'name' => ($sequence->index + 1) . ' BPM',
                    'nickname' => ($sequence->index + 1) . ' BPM',
                    'parent_id' => \App\Models\Unit::where('parent_id', 1)
                        ->inRandomOrder()
                        ->first()
                        ->id,
                    'order' => $sequence->index + 1,
                    'country_id' => $city->country_id,
                    'country_code' => $city->country_code,
                    'state_id' => $city->state_id,
                    'state_code' => $city->state_code,
                    'city_id' => $city->id,
                    'city_name' => $city->name,
                ];
            })->afterCreating(function (\App\Models\Unit $unit) use ($superAdmin, $cities) {
                $order = 0;

                $city = $cities->random();

                $subunits = [
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'Comando',
                        'nickname' => 'Comando',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'Subcomando',
                        'nickname' => 'Subcomando',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/1',
                        'nickname' => 'P/1',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/2',
                        'nickname' => 'P/2',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/3',
                        'nickname' => 'P/3',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/4',
                        'nickname' => 'P/4',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/5',
                        'nickname' => 'P/5',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'P/6',
                        'nickname' => 'P/6',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'PCS',
                        'nickname' => 'PCS',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                    [
                        'owner' => $superAdmin->id,
                        'name' => 'ROTAM',
                        'nickname' => 'ROTAM',
                        'parent_id' => $unit->id,
                        'order' => ++$order,
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                    ],
                ];

                \App\Models\Unit::factory(count($subunits))
                    ->state(new Sequence(...$subunits))->create();

                $city = $cities->random();

                \App\Models\Unit::factory(rand(3, 6))
                    ->sequence(
                        function (Sequence $sequence) use ($superAdmin, $cities, $unit, $order) {
                            $city = $cities->random();

                            return [
                                'owner' => $superAdmin->id,
                                'name' => ($sequence->index + 1) . ' Cia',
                                'nickname' => ($sequence->index + 1) . ' Cia',
                                'parent_id' => $unit->id,
                                'order' => $sequence->index + ++$order,
                                'country_id' => $city->country_id,
                                'country_code' => $city->country_code,
                                'state_id' => $city->state_id,
                                'state_code' => $city->state_code,
                                'city_id' => $city->id,
                                'city_name' => $city->name,
                            ];
                        }
                    )->create();
            })->create();

        \App\Models\Unit::orderBy('id')->chunk(100, function (Collection $units) {
            foreach ($units as $unit) {
                $unit = \App\Models\Unit::where('id', $unit->id)->first();

                $unit->fullpath = $unit->getParentsNames();
                $unit->shortpath = $unit->getParentsNicknames();
                $unit->children_id = collect($unit->getDescendants())->toJson();

                $unit->save();
            }
        });
    }
}
