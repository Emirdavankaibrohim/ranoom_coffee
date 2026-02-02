<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_code' => 1, // Usually string or ID, assumes linked manually or via valid ID
            'user_id' => \App\Models\User::factory(),
            'net_amount' => $this->faker->numberBetween(10000, 500000),
            'paid_amount' => $this->faker->numberBetween(10000, 500000),
            'change_amount' => 0,
            'payment_method' => $this->faker->randomElement(['cash', 'mobile_banking']),
            'status' => '1',
        ];
    }
}
