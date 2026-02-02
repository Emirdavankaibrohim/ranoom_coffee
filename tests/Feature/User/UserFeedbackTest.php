<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_user_can_view_contact_page()
    {
        $response = $this->actingAs($this->user)->get(route('contactus'));
        $response->assertOk();
    }

    public function test_user_can_submit_contact_form()
    {
        $response = $this->actingAs($this->user)->post(route('addContact'), [
            'name' => 'John Doe',
            'phone' => '12345678',
            'inquiry_type' => 'other',
            'message' => 'Hello there',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_contacts', ['phone' => '12345678']);
    }

    public function test_user_can_submit_review()
    {
        // Reviews are not linked to products in current implementation
        $response = $this->actingAs($this->user)->post(route('addReview'), [
            'name' => 'User Name',
            'rating' => '5',
            'subject' => 'Great coffee!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'rating' => '5',
            'subject' => 'Great coffee!'
        ]);
    }
}
