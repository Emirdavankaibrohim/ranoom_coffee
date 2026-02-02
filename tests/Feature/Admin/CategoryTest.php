<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_category_list()
    {
        $response = $this->actingAs($this->admin)->get(route('category.list'));
        $response->assertOk();
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('category.store'), [
            'category' => 'New Category',
        ]);

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('category.update', $category->id), [
            'category' => 'Updated Category',
            'categoryID' => $category->id,
        ]);

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->from(route('category.list'))
            ->delete(route('category.delete', $category->id));

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
