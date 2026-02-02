<?php

namespace Tests\Unit\Models;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_has_fillable_attributes()
    {
        $supplier = new Supplier();
        $this->assertEquals(['name', 'contact', 'address', 'status'], $supplier->getFillable());
    }

    public function test_supplier_creation()
    {
        Supplier::factory()->create(['name' => 'Acme Corp']);
        $this->assertDatabaseHas('suppliers', ['name' => 'Acme Corp']);
    }
}
