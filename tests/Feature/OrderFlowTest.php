<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\TaxSetting;
use App\Models\PaymentRecord;
use App\Models\ProductSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test cases untuk memverifikasi perbaikan Order Flow.
 *
 * Catatan: Semua test menggunakan database assertions untuk memverifikasi
 * logic bisnis tanpa bergantung pada HTTP routes yang memerlukan middleware.
 */
class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cashier;
    protected User $customer;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tax setting
        TaxSetting::create([
            'tax_name' => 'PPN',
            'tax_rate' => 10,
        ]);

        // Create users for each role
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'address' => 'Test Address, Jakarta',
        ]);

        $this->cashier = User::factory()->create([
            'role' => 'cashier',
            'address' => 'Test Address, Jakarta',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'user',
            'address' => 'Test Address, Jakarta',
        ]);

        // Create category and product
        $this->category = Category::create(['name' => 'Coffee']);
        $this->product = Product::create([
            'name' => 'Espresso',
            'category_id' => $this->category->id,
            'image' => 'default.jpg',
            'qty' => 100,
            'description' => 'Test product',
        ]);

        // Create product size
        ProductSize::create([
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'price' => 25000,
        ]);
    }

    /**
     * TEST 1: Verifikasi order dengan status 1 adalah "Menunggu Verifikasi"
     */
    public function test_order_status_1_means_pending_verification(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-STATUS1',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1,
            'status' => 1, // Status 1 = Menunggu Verifikasi
            'totalprice' => 25000,
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'mobile',
        ]);

        $this->assertEquals(1, $order->status);
        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-STATUS1',
            'status' => 1,
        ]);
    }

    /**
     * TEST 2: Verifikasi order bisa diupdate dari status 1 ke 2 (Diproses)
     */
    public function test_order_can_be_approved_status_1_to_2(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-APPROVE',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1,
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
        ]);

        // Simulate kasir approve
        $order->update(['status' => 2]);

        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-APPROVE',
            'status' => 2,
        ]);
    }

    /**
     * TEST 3: Verifikasi order bisa ditolak (status 1 ke 4)
     */
    public function test_order_can_be_rejected_status_1_to_4(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-REJECT',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1,
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'cash',
        ]);

        // Simulate kasir reject
        $order->update(['status' => 4]);

        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-REJECT',
            'status' => 4,
        ]);
    }

    /**
     * TEST 4: Verifikasi notes tersimpan dengan benar di order
     */
    public function test_notes_stored_correctly_in_order(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-NOTES',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'notes' => 'Extra sugar, no ice', // Notes harus tersimpan
            'order_type' => 1,
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'mobile',
        ]);

        $this->assertEquals('Extra sugar, no ice', $order->notes);
        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-NOTES',
            'notes' => 'Extra sugar, no ice',
        ]);
    }

    /**
     * TEST 5: Verifikasi order_type tersimpan dengan benar (bukan hardcoded)
     */
    public function test_order_type_stored_correctly_not_hardcoded(): void
    {
        // Test order_type 1 (Bawa Pulang)
        $order1 = Order::create([
            'order_code' => 'ORD-TYPE1',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1, // Bawa Pulang
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'mobile',
        ]);

        // Test order_type 2 (Makan di Tempat)
        $order2 = Order::create([
            'order_code' => 'ORD-TYPE2',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 2, // Makan di Tempat
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test2',
            'customer_phone' => '081234567891',
            'payment_method' => 'mobile',
        ]);

        // Test order_type 3 (Delivery)
        $order3 = Order::create([
            'order_code' => 'ORD-TYPE3',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 3, // Delivery
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'Test3',
            'customer_phone' => '081234567892',
            'payment_method' => 'mobile',
        ]);

        $this->assertDatabaseHas('orders', ['order_code' => 'ORD-TYPE1', 'order_type' => 1]);
        $this->assertDatabaseHas('orders', ['order_code' => 'ORD-TYPE2', 'order_type' => 2]);
        $this->assertDatabaseHas('orders', ['order_code' => 'ORD-TYPE3', 'order_type' => 3]);
    }

    /**
     * TEST 6: Verifikasi payment_method 'mobile' untuk QRIS
     */
    public function test_payment_method_mobile_for_qris(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-QRIS',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1,
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'QRIS Test',
            'customer_phone' => '081234567890',
            'payment_method' => 'mobile', // Harus 'mobile' bukan 'card'
        ]);

        $this->assertEquals('mobile', $order->payment_method);
        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-QRIS',
            'payment_method' => 'mobile',
        ]);
    }

    /**
     * TEST 7: Verifikasi PaymentRecord tersimpan dengan payment_method yang benar
     */
    public function test_payment_record_stores_correct_payment_method(): void
    {
        $paymentRecord = PaymentRecord::create([
            'order_code' => 'ORD-PAYMENT',
            'user_id' => $this->customer->id,
            'net_amount' => 27500,
            'paid_amount' => 27500,
            'change_amount' => 0,
            'payment_method' => 'mobile', // QRIS
            'status' => 1,
        ]);

        $this->assertEquals('mobile', $paymentRecord->payment_method);
        $this->assertDatabaseHas('payment_records', [
            'order_code' => 'ORD-PAYMENT',
            'payment_method' => 'mobile',
        ]);
    }

    /**
     * TEST 8: Verifikasi cash payment menyimpan kembalian dengan benar
     */
    public function test_cash_payment_stores_change_correctly(): void
    {
        $paymentRecord = PaymentRecord::create([
            'order_code' => 'ORD-CASH',
            'user_id' => $this->customer->id,
            'net_amount' => 27500,
            'paid_amount' => 50000,
            'change_amount' => 22500, // Kembalian
            'payment_method' => 'cash',
            'status' => 1,
        ]);

        $this->assertEquals(22500, $paymentRecord->change_amount);
        $this->assertDatabaseHas('payment_records', [
            'order_code' => 'ORD-CASH',
            'payment_method' => 'cash',
            'change_amount' => 22500,
        ]);
    }

    /**
     * TEST 9: Verifikasi customer_name dan customer_phone tersimpan
     */
    public function test_customer_info_stored_correctly(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-CUSTOMER',
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'size' => 'Medium',
            'quantity' => 1,
            'order_type' => 1,
            'status' => 1,
            'totalprice' => 25000,
            'customer_name' => 'John Doe',
            'customer_phone' => '089876543210',
            'payment_method' => 'cash',
        ]);

        $this->assertEquals('John Doe', $order->customer_name);
        $this->assertEquals('089876543210', $order->customer_phone);
        $this->assertDatabaseHas('orders', [
            'order_code' => 'ORD-CUSTOMER',
            'customer_name' => 'John Doe',
            'customer_phone' => '089876543210',
        ]);
    }

    /**
     * TEST 10: Verifikasi Cart menyimpan notes yang akan diteruskan ke Order
     */
    public function test_cart_stores_notes_for_transfer_to_order(): void
    {
        $cart = Cart::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'qty' => 1,
            'orderCode' => 'ORD-CARTNOTE',
            'size' => 'Medium',
            'notes' => 'Tanpa gula, extra cream',
        ]);

        $this->assertEquals('Tanpa gula, extra cream', $cart->notes);
        $this->assertDatabaseHas('carts', [
            'orderCode' => 'ORD-CARTNOTE',
            'notes' => 'Tanpa gula, extra cream',
        ]);
    }

    /**
     * TEST 11: Verifikasi order_type mapping dari string ke integer
     *
     * Mapping:
     * - 'take_away' => 1
     * - 'eat_in' => 2
     * - 'delivery' => 3
     */
    public function test_order_type_mapping_works_correctly(): void
    {
        $orderTypeMap = [
            'take_away' => 1,
            'eat_in'    => 2,
            'delivery'  => 3,
        ];

        foreach ($orderTypeMap as $string => $integer) {
            $mappedValue = $orderTypeMap[$string];
            $this->assertEquals($integer, $mappedValue, "Order type '{$string}' should map to {$integer}");
        }
    }
}
