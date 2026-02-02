<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFeesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'city' => $this->faker->city(),
            'township' => $this->faker->streetName(),
            'fees' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
