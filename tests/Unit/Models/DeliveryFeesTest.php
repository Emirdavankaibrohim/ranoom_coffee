<?php

namespace Tests\Unit\Models;

use App\Models\DeliveryFees;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryFeesTest extends TestCase
{
    use RefreshDatabase;

    public function test_delivery_fees_has_fillable_attributes()
    {
        $fees = new DeliveryFees();
        $this->assertEquals(['city', 'township', 'fees'], $fees->getFillable());
    }

    public function test_delivery_fees_creation()
    {
        $fees = DeliveryFees::factory()->create([
            'city' => 'Yangon',
            'township' => 'Sanchaung',
            'fees' => 3000
        ]);

        $this->assertDatabaseHas('delivery_fees', [
            'city' => 'Yangon',
            'township' => 'Sanchaung',
            'fees' => 3000
        ]);
    }

    public function test_delivery_fees_numeric_check()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        // Assuming database schema enforces integer/float, verification by attempting to insert string
        DeliveryFees::create([
            'city' => 'Yangon',
            'township' => 'Bahan',
            'fees' => 'invalid_fee' // Should fail if strict types or trigger DB error
        ]);
    }
}
