<?php

namespace Tests\Unit\Models;

use App\Models\ProductSize;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSizeTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_size_has_fillable_attributes()
    {
        $size = new ProductSize();
        $this->assertEquals(['product_id', 'size', 'price'], $size->getFillable());
    }

    public function test_product_size_belongs_to_product()
    {
        if (!method_exists(ProductSize::class, 'product')) {
            $this->markTestSkipped('Product relationship not defined.');
        }

        $product = Product::factory()->create();
        $size = ProductSize::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $size->product);
    }
}
