<?php

namespace App\Services;

use App\Interfaces\ApiTokenServiceInterface;
use Illuminate\Support\Facades\Http;

class ApiTokenService implements ApiTokenServiceInterface
{
    public $clientId;
    public $clientSecret;
    public $email;
    public $password;

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBearer()
    {
        $responseToken = Http::timeout(9999999)->post('http://127.0.0.1:8000/oauth/token', [
            "grant_type" => "authorization_code",
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "username" => $this->email,
            "password" => $this->password,
            "scope" => "",
        ]);
        return json_decode($responseToken->body(), true);
    }

}
