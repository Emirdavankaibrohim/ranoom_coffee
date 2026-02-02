<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'inquiry_type' => $this->faker->word(),
            'message' => $this->faker->paragraph(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
