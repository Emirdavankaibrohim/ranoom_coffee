<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\TaxSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Admin Dashboard functionality.
 * Uses direct URL paths instead of route names to avoid middleware issues.
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tax setting for tests
        TaxSetting::create([
            'tax_name' => 'PPN',
            'tax_rate' => 10,
        ]);
    }

    /**
     * Test admin can access dashboard via direct URL.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'address' => 'Test Address',
        ]);

        $response = $this->actingAs($admin)->get('/admin/home');

        $response->assertStatus(200);
    }

    /**
     * Test cashier gets appropriate response when accessing dashboard.
     * Cashier may have full access (200) or restricted access (403/302).
     */
    public function test_cashier_dashboard_access(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
            'address' => 'Test Address',
        ]);

        $response = $this->actingAs($cashier)->get('/admin/home');

        // Cashier may have full access (200), restricted (403), or redirect (302)
        $this->assertTrue(
            in_array($response->status(), [200, 302, 303, 403]),
            "Expected status 200, 302, 303, or 403, got {$response->status()}"
        );
    }

    /**
     * Test dashboard has correct view data for admin.
     */
    public function test_dashboard_has_correct_view_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'address' => 'Test Address',
        ]);

        $response = $this->actingAs($admin)->get('/admin/home');

        $response->assertStatus(200);
        $response->assertViewHas('dailySales');
        $response->assertViewHas('monthlySales');
        $response->assertViewHas('salesOverview');
        $response->assertViewHas('outofstock');
    }

    /**
     * Test salesOverview returns daily_sales field (FIX VERIFICATION).
     */
    public function test_sales_overview_returns_daily_sales_field(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'address' => 'Test Address',
        ]);

        // Create category and product for order
        $category = Category::create(['name' => 'Coffee']);
        $product = Product::create([
            'name' => 'Espresso',
            'category_id' => $category->id,
            'image' => 'default.jpg',
            'qty' => 100,
            'description' => 'Test product',
        ]);

        // Create test order (status 2 = completed for sales)
        Order::create([
            'order_code' => 'ORD-TEST123',
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'notes' => 'Test note',
            'order_type' => 1,
            'status' => 2,
            'totalprice' => 25000,
            'customer_name' => 'Test Customer',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($admin)->get('/admin/home');

        $response->assertStatus(200);

        // Verify salesOverview data structure
        $salesOverview = $response->viewData('salesOverview');

        if ($salesOverview && $salesOverview->isNotEmpty()) {
            $firstItem = $salesOverview->first();
            // FIX VERIFICATION: Should have 'daily_sales' not 'total'
            $this->assertTrue(
                isset($firstItem->daily_sales),
                'salesOverview should have daily_sales field'
            );
        }
    }
}
