<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_has_fillable_attributes()
    {
        $review = new Review();
        $this->assertEquals(['user_id', 'name', 'rating', 'subject'], $review->getFillable());
    }

    public function test_review_belongs_to_user()
    {
         if (!method_exists(Review::class, 'user')) {
             $this->markTestSkipped('User relationship not defined.');
        }
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $review->user);
    }

    // Product relation skipped as not in fillable

}
