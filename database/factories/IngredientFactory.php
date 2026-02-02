<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'cost_price' => $this->faker->numberBetween(100, 5000),
            'unit' => $this->faker->randomElement(['kg', 'g', 'liter', 'ml', 'pcs']),
        ];
    }
}
