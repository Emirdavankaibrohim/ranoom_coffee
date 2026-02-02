<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
            'qty' => fake()->numberBetween(1, 5),
            'orderCode' => 'ORD-' . strtoupper(fake()->unique()->lexify('??????')),
            'size' => fake()->randomElement(['Small', 'Medium', 'Large']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
