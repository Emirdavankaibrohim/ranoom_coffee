<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSizeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'size' => $this->faker->randomElement(['Regular', 'Large', 'Extra Large']),
            'price' => $this->faker->numberBetween(1000, 5000),
        ];
    }
}
