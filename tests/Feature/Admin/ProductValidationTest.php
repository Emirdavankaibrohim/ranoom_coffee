<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::factory()->create();
    }

    public function test_product_creation_requires_name()
    {
        $response = $this->actingAs($this->admin)->post(route('product.prodstore'), [
            'name' => '',
            'category_name' => $this->category->id,
            'stock' => 10,
            'description' => 'Desc',
        ]);
        $response->assertSessionHasErrors('name');
    }

    public function test_product_creation_requires_category()
    {
        $response = $this->actingAs($this->admin)->post(route('product.prodstore'), [
            'name' => 'Coffee',
            'category_name' => '',
            'stock' => 10,
            'description' => 'Desc',
        ]);
        $response->assertSessionHasErrors('category_name');
    }

    // Price validation removed as it is not part of initial product creation

    public function test_product_creation_requires_stock()
    {
        $response = $this->actingAs($this->admin)->post(route('product.prodstore'), [
            'name' => 'Coffee',
            'category_name' => $this->category->id,
            'stock' => '',
            'description' => 'Desc',
        ]);
        $response->assertSessionHasErrors('stock');
    }

    public function test_product_creation_stock_must_be_integer()
    {
        $response = $this->actingAs($this->admin)->post(route('product.prodstore'), [
            'name' => 'Coffee',
            'category_name' => $this->category->id,
            'stock' => 'abc',
            'description' => 'Desc',
        ]);
        $response->assertSessionHasErrors('stock');
    }
}
