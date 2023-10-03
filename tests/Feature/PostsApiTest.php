<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PostsApiTest extends TestCase
{
    use BaseTest;

    public function test_create_post()
    {
        $response = $this->createUser();
        $response->assertStatus(ResponseAlias::HTTP_CREATED);
        $content = json_decode($response->getContent(), true);
        $userId = $content['data']['id'];
        $userEmail = $content['data']['attributes']['email'];
        $userName = $content['data']['attributes']['name'];

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
            "id" => $userId
        ]);

        $contentActivateUser = json_decode($responseActivate->body(), true);

        $this->assertEquals('active', $contentActivateUser['data']['attributes']['status']);

        $responseLoginUser = $this->loginUser($userEmail, '12345678');
        $responseLoginUser->assertStatus(ResponseAlias::HTTP_OK);

        $userClient = $clientRepository->create(
            $userId, $userName, '', null, false, true
        );

        $userResponseToken = $this->createUserToken($userClient, $userEmail);
        $userResponseBody = json_decode($userResponseToken->getContent(), true);

        $userToken = $userResponseBody['access_token'];

        $responsePosts = Http::withToken($userToken)->post('http://127.0.0.1:8000/api/posts', [
            'author' => 'New Functional Test',
            'content' => 'New Functional Test',
        ]);

        $this->assertEquals($responsePosts->status(), ResponseAlias::HTTP_CREATED);
    }
}
