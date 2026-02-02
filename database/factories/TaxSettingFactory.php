<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tax_name' => $this->faker->word(),
            'tax_rate' => $this->faker->randomFloat(2, 1, 15),
        ];
    }
}
