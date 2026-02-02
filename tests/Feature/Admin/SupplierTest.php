<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_supplier_list()
    {
        $response = $this->actingAs($this->admin)->get(route('supplier.index'));
        $response->assertOk();
    }

    public function test_admin_can_create_supplier()
    {
        $supplierData = Supplier::factory()->make()->toArray();

        $response = $this->actingAs($this->admin)->post(route('createSupplier'), $supplierData);

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseHas('suppliers', ['name' => $supplierData['name']]);
    }

    public function test_admin_can_update_supplier()
    {
        $supplier = Supplier::factory()->create();
        $newData = Supplier::factory()->make()->toArray();
        $newData['paid_amount'] = 0;

        $response = $this->actingAs($this->admin)->post(route('updateSupplier', $supplier->id), $newData);

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => $newData['name']]);
    }

    public function test_admin_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['status' => 'Inactive']);

        $response = $this->actingAs($this->admin)->delete(route('deleteSupplier', $supplier->id));

        $response->assertRedirect(route('supplier.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
