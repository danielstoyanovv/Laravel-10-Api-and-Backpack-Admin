<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ApiTokenServiceInterface;
use Illuminate\Support\Facades\Http;

class OauthTokenAdapterService implements ApiTokenServiceInterface
{
    public $clientId;
    public $clientSecret;
    public $email;
    public $password;

    /**
     * @param int $clientId`
     * @return $this`
     */
    public function setClientId(int $clientId): self
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

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken(): mixed
    {
        $responseToken = Http::timeout(9999999)->post('http://127.0.0.1:8000/oauth/token', [
            "grant_type" => "password",
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret,
            "username" => $this->email,
            "password" => $this->password,
            "scope" => "",
        ]);
        return json_decode($responseToken->body(), true);
    }

}
