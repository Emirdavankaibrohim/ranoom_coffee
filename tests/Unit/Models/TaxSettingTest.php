<?php

namespace Tests\Unit\Models;

use App\Models\TaxSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tax_setting_has_fillable_attributes()
    {
        $tax = new TaxSetting();
        $this->assertEquals(['tax_name', 'tax_rate'], $tax->getFillable());
    }

    public function test_tax_setting_creation()
    {
        TaxSetting::factory()->create(['tax_name' => 'VAT']);
        $this->assertDatabaseHas('tax_settings', ['tax_name' => 'VAT']);
    }
}
