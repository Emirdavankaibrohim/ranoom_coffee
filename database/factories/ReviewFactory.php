<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->name(),
            'rating' => $this->faker->numberBetween(1, 5),
            'subject' => $this->faker->sentence(),
        ];
    }
}
