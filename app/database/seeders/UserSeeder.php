<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $cities = \App\Models\City::where('state_id', 2022)->get();
        $units = \App\Models\Unit::get()->pluck('id');
        $superAdmin = \App\Models\User::where('system_role', true)->first();

        \App\Models\User::factory(rand(180, 200))
            ->afterCreating(function (\App\Models\User $user) use ($cities, $units, $superAdmin) {
                $user->units()->attach($units->random(), ['owner' => $superAdmin->id, 'created_at' => now()]);

                $city = $cities->random();

                $documents = [
                    [
                        'owner' => $user->id,
                        'type' => 1,
                        'value' => fake()->unique()->bothify('#.###.###-#'),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 2,
                        'value' => fake()->unique()->bothify('###.###.###-##'),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 3,
                        'value' => fake()->unique()->regexify('[0-9]{16}'),
                        'category' => collect(['A', 'B', 'C', 'D', 'E', 'AB', 'AC', 'AD', 'AE'])->random(),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 4,
                        'value' => fake()->unique()->regexify('[0-9]{16}'),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 5,
                        'value' => fake()->unique()->regexify('[0-9]{16}'),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 6,
                        'value' => Str::upper(Str::random(16)),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 7,
                        'value' => fake()->unique()->bothify('###.###.###-#'),
                        'issued_at' => fake()->dateTimeBetween('-20 year', '-1 month'),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'expires_at' => fake()->dateTimeBetween('+1 year', '+4 year'),
                        'primary' => true,
                    ],
                ];

                \App\Models\UserDocument::factory(count($documents))
                    ->state(new Sequence(...$documents))
                    ->create();

                $contacts = [
                    [
                        'owner' => $user->id,
                        'type' => 1,
                        'value' => fake()->unique()->landlineNumber(),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 2,
                        'value' => fake()->unique()->cellphoneNumber(),
                        'primary' => true,
                    ],
                    [
                        'owner' => $user->id,
                        'type' => 3,
                        'value' => $user->email,
                        'primary' => true,
                    ],
                ];

                \App\Models\UserContact::factory(count($contacts))
                    ->state(new Sequence(...$contacts))
                    ->create();

                $city = $cities->random();

                $locations = [
                    [
                        'owner' => $user->id,
                        'type' => 1,
                        'address' => fake()->streetName() . ', ' . fake()->buildingNumber(),
                        'complement' => fake()->secondaryAddress(),
                        'country_id' => $city->country_id,
                        'country_code' => $city->country_code,
                        'state_id' => $city->state_id,
                        'state_code' => $city->state_code,
                        'city_id' => $city->id,
                        'city_name' => $city->name,
                        'postcode' => fake()->postcode(),
                        'latitude' => fake()->latitude('-26', '-22'),
                        'longitude' => fake()->longitude('-55', '-48'),
                        'primary' => true,
                    ],
                ];

                \App\Models\UserLocation::factory(count($locations))
                    ->state(new Sequence(...$locations))
                    ->create();
            })->create();
    }
}
