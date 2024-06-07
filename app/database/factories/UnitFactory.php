<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'parent_id' => fake()->randomNumber(1, false),
            'nickname' => fake()->company(),
            'founded' => fake()->dateTimeBetween('-100 year', '-1 month'),
            'active' => fake()->boolean(),
            'expires_at' => fake()->dateTimeBetween('-1 year', '+9 month'),
            'cellphone' => fake()->cellphoneNumber(),
            'landline' => fake()->landlineNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetName() . ', ' . fake()->buildingNumber(),
            'complement' => fake()->secondaryAddress(),
            'postcode' => fake()->postcode(),
            'latitude' => fake()->latitude('-26', '-22'),
            'longitude' => fake()->longitude('-55', '-48'),
        ];
    }
}
