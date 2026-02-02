<?php

namespace Tests\Unit\Models;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_has_fillable_attributes()
    {
        $fillable = [
            'name', 'asset_category_id', 'assigned_user_id', 'purchase_date',
            'purchase_value', 'depreciation_rate', 'status', 'unit',
            'warranty_expiry_date', 'serial_number', 'notes'
        ];

        $asset = new Asset();
        $this->assertEquals($fillable, $asset->getFillable());
    }

    public function test_asset_belongs_to_category()
    {
        $category = AssetCategory::factory()->create();
        $asset = Asset::factory()->create(['asset_category_id' => $category->id]);

        $this->assertInstanceOf(AssetCategory::class, $asset->category);
        $this->assertEquals($category->id, $asset->category->id);
    }

    public function test_asset_belongs_to_assigned_user()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create(['assigned_user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $asset->assignedUser);
        $this->assertEquals($user->id, $asset->assignedUser->id);
    }

    public function test_asset_can_be_created()
    {
        $assetData = Asset::factory()->make()->toArray();
        $asset = Asset::create($assetData);

        $this->assertDatabaseHas('assets', ['id' => $asset->id]);
    }
}
