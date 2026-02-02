<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_user_can_view_menu()
    {
        $response = $this->actingAs($this->user)->get(route('climenu'));
        $response->assertOk();
    }

    public function test_user_can_filter_menu_by_category()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->user)->get(route('climenu', ['category_id' => $category->id]));
        $response->assertOk();
    }

    public function test_user_can_view_cart()
    {
        $response = $this->actingAs($this->user)->get(route('cartPage'));
        $response->assertOk();
    }

    public function test_user_can_add_to_cart()
    {
        $product = Product::factory()->create();

        $this->user->update(['address' => '123 Main St']);
        
        $response = $this->actingAs($this->user)->post(route('addToCart', $product->id), [
            'product_id' => $product->id, // Controller reads from request input, not route param
            'quantity' => 1, // Controller expects quantity, not qty
            'size' => 'Medium',
            'notes' => 'Less sugar',
        ]);

        $response->assertRedirect(); // Likely back() or to cart
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'qty' => 1
        ]);
    }

    public function test_user_can_remove_from_cart()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->post(route('removeCart', $cart->id));

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }
}
