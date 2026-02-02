<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = AssetCategory::factory()->create();
    }

    public function test_admin_can_view_assets()
    {
        $response = $this->actingAs($this->admin)->get(route('assets.index'));
        $response->assertOk();
    }

    public function test_admin_can_create_asset()
    {
        $assetData = Asset::factory()->make([
            'asset_category_id' => $this->category->id
        ])->toArray();

        // Convert boolean/nulls if necessary for POST request, though factory should match
        $response = $this->actingAs($this->admin)->post(route('assets.store'), $assetData);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', ['serial_number' => $assetData['serial_number']]);
    }

    public function test_admin_can_update_asset()
    {
        $asset = Asset::factory()->create(['asset_category_id' => $this->category->id]);
        $newData = Asset::factory()->make(['asset_category_id' => $this->category->id])->toArray();

        $response = $this->actingAs($this->admin)->put(route('assets.update', $asset->id), $newData);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', ['id' => $asset->id, 'serial_number' => $newData['serial_number']]);
    }

    public function test_admin_can_delete_asset()
    {
        $asset = Asset::factory()->create(['asset_category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)->delete(route('assets.destroy', $asset->id));

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }
}
