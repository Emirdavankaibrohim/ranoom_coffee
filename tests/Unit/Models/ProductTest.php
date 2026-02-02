<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\ProductSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_fillable_attributes()
    {
        $product = new Product();
        $this->assertEquals(['name', 'qty', 'category_id', 'description', 'image'], $product->getFillable());
    }

    public function test_product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
    }

    public function test_product_has_many_sizes()
    {
        $product = Product::factory()->create();
        $size = ProductSize::factory()->create(['product_id' => $product->id]);

        $this->assertTrue($product->sizes->contains('id', $size->id));
    }

    // Review relation removed as schema does not support it

}
