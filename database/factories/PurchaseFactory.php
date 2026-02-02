<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\Supplier::factory(),
            'total_amount' => $this->faker->numberBetween(100000, 5000000),
            'paid_amount' => $this->faker->numberBetween(0, 5000000),
            'due_amount' => 0, // Simplified logic
            'payment_status' => $this->faker->randomElement(['pending', 'partial', 'paid']),
        ];
    }
}
