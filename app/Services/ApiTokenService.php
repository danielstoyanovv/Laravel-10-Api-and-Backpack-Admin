<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiTokenService
{
    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function getBearer(string $clientId, string $clientSecret, string $email, string $password)
    {
        $responseToken = Http::timeout(9999999)->post('http://127.0.0.1:8000/oauth/token', [
            "grant_type" => "password",
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            "username" => $email,
            "password" => $password,
            "scope" => "",
        ]);
        return json_decode($responseToken->body(), true);
    }

}
