<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'asset_category_id' => \App\Models\AssetCategory::factory(),
            'assigned_user_id' => \App\Models\User::factory(),
            'purchase_date' => $this->faker->date(),
            'purchase_value' => $this->faker->randomFloat(2, 100, 10000),
            'depreciation_rate' => $this->faker->randomFloat(2, 1, 20),
            'status' => $this->faker->randomElement(['active', 'inactive', 'maintenance']),
            'unit' => $this->faker->word(),
            'warranty_expiry_date' => $this->faker->date(),
            'serial_number' => $this->faker->uuid(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
