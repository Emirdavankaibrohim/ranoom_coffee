<?php

namespace Tests\Unit\Models;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredient_has_fillable_attributes()
    {
        $ingredient = new Ingredient();
        $this->assertEquals(['name', 'cost_price', 'unit'], $ingredient->getFillable());
    }

    public function test_ingredient_creation()
    {
        $ingredient = Ingredient::factory()->create([
            'name' => 'Coffee Beans',
            'cost_price' => 20000,
            'unit' => 'kg'
        ]);

        $this->assertDatabaseHas('ingredients', ['name' => 'Coffee Beans']);
    }
}
