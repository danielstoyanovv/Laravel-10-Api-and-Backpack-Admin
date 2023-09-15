<?php

namespace Tests\Feature;

use Tests\TestCase;

class UsersApiTest extends TestCase
{
    public function test_create_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Functional Test",
            "email" => uniqid() . "@gmail.com",
            "password" => "12345678",
            "short_description" => "tttttttttttttttttttttttttttt",
            "image_content" => "http://127.0.0.1:8000/uploads/400.png"
        ]);

        $response->assertStatus(201);
    }
}
