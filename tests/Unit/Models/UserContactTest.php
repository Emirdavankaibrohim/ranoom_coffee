<?php

namespace Tests\Unit\Models;

use App\Models\UserContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_contact_has_fillable_attributes()
    {
        $contact = new UserContact();
        $this->assertEquals(['name', 'phone', 'inquiry_type', 'message', 'user_id'], $contact->getFillable());
    }
}
