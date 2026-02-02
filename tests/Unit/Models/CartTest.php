<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_has_fillable_attributes()
    {
        $cart = new Cart();
        $this->assertEquals(['user_id', 'product_id', 'qty', 'orderCode', 'size', 'notes'], $cart->getFillable());
    }

    public function test_cart_belongs_to_user()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $cart->user);
    }

    public function test_cart_belongs_to_product()
    {
        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $cart->product);
    }
}
