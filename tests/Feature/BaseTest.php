<?php

namespace Tests\Feature;

use Laravel\Passport\Client;

trait BaseTest
{
    public function createUser()
    {
        return $this->postJson('/api/users', [
            "name" => "Functional Test",
            "email" => uniqid() . "@gmail.com",
            "password" => "12345678",
            "short_description" => "tttttttttttttttttttttttttttt",
   //         "image_content" => "http://127.0.0.1:8000/uploads/400.png"
            //uncomment image_content when you update it with a real image path which exists on your server
        ]);
    }

    public function loginUser(string $email, string $password)
    {
        return $this->postJson('/api/login', [
            'email' => $email,
            'password' =>  $password,
        ]);
    }

    public function createUserToken(string $email, string $password)
    {
        return $this->postJson('/api/token', [
            'email' => $email,
            'password' =>  $password
        ]);
    }
}
