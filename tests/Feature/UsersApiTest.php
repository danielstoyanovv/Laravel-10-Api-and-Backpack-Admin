<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UsersApiTest extends TestCase
{
    public function test_create_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Functional Test",
            "email" => uniqid() . "@gmail.com",
            "password" => "12345678",
            "short_description" => "tttttttttttttttttttttttttttt",
            //"image_content" => "http://127.0.0.1:8000/uploads/400.png"
            //uncomment image_content when you update it with a real image path which exists on your server
        ]);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);
    }

    public function test_activate_user()
    {
        $response = $this->postJson('/api/users', [
            "name" => "Functional Test",
            "email" => uniqid() . "@gmail.com",
            "password" => "12345678",
            "short_description" => "tttttttttttttttttttttttttttt"
        ]);
        $response->assertStatus(ResponseAlias::HTTP_CREATED);
        $content = json_decode($response->getContent(), true);

        $response = $this->postJson('/api/activate-user', [
            "id" => $content['data']['id']
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson('/api/activate-user', [
            "id" => $content['data']['id'],
            "token" => 123
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        $responseLogin = $this->postJson('/api/login', [
            'email' => 'superAdmin8674@gmail.com',
            'password' => '12345678',
        ]);
        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        $responseTokenUpdate = $this->postJson('/api/token-update', [
            'refresh_token' => 'iupebvxumu8g8k4o00oc4ook408sw0w'
        ]);
        $contentTokenUpdate = json_decode($responseTokenUpdate->getContent(), true);
        $responseTokenUpdate->assertStatus(ResponseAlias::HTTP_OK);

        $responseLogin2 = $this->postJson('/api/login', [
            'email' => 'superAdmin8674@gmail.com',
            'password' => '12345678',
        ]);
        $contentLogin2 = json_decode($responseLogin2->getContent(), true);

        $responseActivateUser = $this->postJson('/api/activate-user', [
            "id" => $content['data']['id'],
            "token" => $contentLogin2['data']['attributes']['token']
        ]);

        $contentActivateUser = json_decode($responseActivateUser->getContent(), true);

        $this->assertEquals('active', $contentActivateUser['data']['attributes']['status']);
    }
}
