<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Laravel\Passport\ClientRepository;

class UsersApiTest extends TestCase
{
    use BaseTest;

    public function test_create_user()
    {
        $response = $this->createUser();
        $response->assertStatus(ResponseAlias::HTTP_CREATED);
    }

    public function test_activate_user()
    {
        $response = $this->createUser();
        $response->assertStatus(ResponseAlias::HTTP_CREATED);
        $content = json_decode($response->getContent(), true);

        $responseLogin = $this->loginUser('superAdmin8674@gmail.com', '12345678');
        $responseLogin->assertStatus(ResponseAlias::HTTP_OK);

        $clientRepository = new ClientRepository();
        $authUserId = auth()->user()->id;
        if (!empty($clientRepository->forUser($authUserId))) {
            $client = $clientRepository->forUser($authUserId)->first();
        } else {
            $client = $clientRepository->create(
                $authUserId, auth()->user()->name, '', null, false, true
            );
        }
        $responseToken = $this->createUserToken($client, auth()->user()->email);
        $responseBody = json_decode($responseToken->getContent(), true);

        $token = $responseBody['access_token'];

        $responseActivate = Http::withToken($token)->post('http://127.0.0.1:8000/api/activate-user', [
            "id" => $content['data']['id']
        ]);

        $contentActivateUser = json_decode($responseActivate->body(), true);

        $this->assertEquals('active', $contentActivateUser['data']['attributes']['status']);
    }
}
