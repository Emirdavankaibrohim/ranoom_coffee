<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_report_overview()
    {
        $response = $this->actingAs($this->admin)->get(route('reportOverview'));
        $response->assertOk();
    }

    public function test_admin_can_view_sales_report_page()
    {
        $response = $this->actingAs($this->admin)->get(route('salesReportPage'));
        $response->assertOk();
    }

    public function test_admin_can_view_inventory_page()
    {
        $response = $this->actingAs($this->admin)->get(route('inventoryPage'));
        $response->assertOk();
    }

    public function test_admin_can_view_product_analysis()
    {
        $response = $this->actingAs($this->admin)->get(route('productAnalysis'));
        $response->assertOk();
    }

    public function test_admin_can_view_supplier_purchase_page()
    {
        $response = $this->actingAs($this->admin)->get(route('supplierPurchasePage'));
        $response->assertOk();
    }

    public function test_admin_can_view_asset_page()
    {
        $response = $this->actingAs($this->admin)->get(route('assetPage'));
        $response->assertOk();
    }
}
