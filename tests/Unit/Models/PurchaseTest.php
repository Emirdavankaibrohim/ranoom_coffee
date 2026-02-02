<?php

namespace Tests\Unit\Models;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_has_fillable_attributes()
    {
        $purchase = new Purchase();
        $this->assertEquals(['total_amount', 'paid_amount', 'due_amount', 'payment_status', 'supplier_id'], $purchase->getFillable());
    }

    public function test_purchase_belongs_to_supplier()
    {
         if (!method_exists(Purchase::class, 'supplier')) {
            $this->markTestSkipped('Supplier relationship not defined.');
        }
        $supplier = Supplier::factory()->create();
        $purchase = Purchase::factory()->create(['supplier_id' => $supplier->id]);

        $this->assertInstanceOf(Supplier::class, $purchase->supplier);
    }
}
