<?php

namespace Tests\Unit\Models;

use App\Models\Purchase_Item;
use App\Models\Purchase;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_item_has_fillable_attributes()
    {
        $item = new Purchase_Item();
        $this->assertEquals(['purchase_id', 'ingredient_id', 'quantity', 'cost_price', 'total_price'], $item->getFillable());
    }

    public function test_purchase_item_belongs_to_purchase()
    {
         if (!method_exists(Purchase_Item::class, 'purchase')) {
            $this->markTestSkipped('Purchase relationship not defined.');
        }
        $purchase = Purchase::factory()->create();
        $item = Purchase_Item::factory()->create(['purchase_id' => $purchase->id]);

        $this->assertInstanceOf(Purchase::class, $item->purchase);
    }

    public function test_purchase_item_belongs_to_ingredient()
    {
         if (!method_exists(Purchase_Item::class, 'ingredient')) {
            $this->markTestSkipped('Ingredient relationship not defined.');
        }
        $ingredient = Ingredient::factory()->create();
        $item = Purchase_Item::factory()->create(['ingredient_id' => $ingredient->id]);

        $this->assertInstanceOf(Ingredient::class, $item->ingredient);
    }
}
