<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'product_id' => \App\Models\Product::factory(),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['0', '1', '2']),
            'order_code' => 'ORD-' . strtoupper(Str::random(6)),
            'quantity' => $this->faker->numberBetween(1, 5),
            'totalprice' => $this->faker->randomFloat(2, 10, 500),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'mobile']),
            'order_type' => $this->faker->randomElement(['eat-in', 'takeaway', 'delivery']),
            'size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
