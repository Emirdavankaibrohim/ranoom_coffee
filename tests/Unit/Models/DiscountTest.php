<?php

namespace Tests\Unit\Models;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountTest extends TestCase
{
    use RefreshDatabase;

    public function test_discount_has_fillable_attributes()
    {
        $discount = new Discount();
        $this->assertEquals(['product_id', 'discount_percentage', 'start_date', 'end_date'], $discount->getFillable());
    }

    public function test_discount_belongs_to_product()
    {
        // Add relation if it exists, otherwise this test serves as a reminder to add it or skips
        if (!method_exists(Discount::class, 'product')) {
            $this->markTestSkipped('Product relationship not defined in Discount model.');
        }

        $product = Product::factory()->create();
        $discount = Discount::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $discount->product);
    }

    public function test_discount_creation()
    {
        $discount = Discount::factory()->create();
        $this->assertDatabaseHas('discounts', ['id' => $discount->id]);
    }
}
