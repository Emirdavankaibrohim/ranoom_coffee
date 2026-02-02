<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\TaxSetting;
use App\Models\DeliveryFees;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_tax_page()
    {
        $response = $this->actingAs($this->admin)->get(route('taxPage'));
        $response->assertOk();
    }

    public function test_admin_can_add_tax_rate()
    {
        $response = $this->actingAs($this->admin)->post(route('addTaxRate'), [
            'tax_name' => 'VAT',
            'tax_rate' => 10,
            'action' => 'add',
        ]);

        $response->assertRedirect(route('taxPage'));
        $this->assertDatabaseHas('tax_settings', ['tax_name' => 'VAT']);
    }

    public function test_admin_can_view_delivery_info_page()
    {
        $response = $this->actingAs($this->admin)->get(route('deliveryInfoPage'));
        $response->assertOk();
    }

    public function test_admin_can_add_delivery_fees()
    {
        $response = $this->actingAs($this->admin)->post(route('addDeliFees'), [
            'city' => 'Yangon',
            'township' => 'Sanchaung',
            'deli_fees' => 3000,
            'action' => 'add',
        ]);

        $response->assertRedirect(route('deliveryInfoPage'));
        $this->assertDatabaseHas('delivery_fees', ['township' => 'Sanchaung']);
    }
}
