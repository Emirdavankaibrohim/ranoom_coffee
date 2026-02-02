<?php

namespace Tests\Unit\Models;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_category_has_fillable_attributes()
    {
        $assetCategory = new AssetCategory();
        $this->assertEquals(['name'], $assetCategory->getFillable());
    }

    public function test_asset_category_has_many_assets()
    {
        $category = AssetCategory::factory()->create();
        $asset = Asset::factory()->create(['asset_category_id' => $category->id]);

        $this->assertTrue($category->assets->contains($asset));
        $this->assertInstanceOf(Asset::class, $category->assets->first());
    }

    public function test_asset_category_creation()
    {
        $category = AssetCategory::factory()->create(['name' => 'Electronics']);
        $this->assertDatabaseHas('asset_categories', ['name' => 'Electronics']);
    }
}
