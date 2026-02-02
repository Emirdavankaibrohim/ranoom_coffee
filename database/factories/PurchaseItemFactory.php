<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    protected $model = \App\Models\Purchase_Item::class;

    public function definition(): array
    {
        return [
            'purchase_id' => \App\Models\Purchase::factory(),
            'ingredient_id' => \App\Models\Ingredient::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'cost_price' => $this->faker->numberBetween(500, 10000),
            'total_price' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['cost_price'];
            },
        ];
    }
}
