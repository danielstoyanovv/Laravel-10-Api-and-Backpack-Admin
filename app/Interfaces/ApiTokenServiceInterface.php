<?php

namespace App\Interfaces;

interface ApiTokenServiceInterface
{
    /**
     * @param int $clientId
     * @return \App\Services\ApiTokenService
     */
    public function setClientId(int $clientId): \App\Services\ApiTokenService;

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret(string $clientSecret): \App\Services\ApiTokenService;

    public function setEmail(string $email): \App\Services\ApiTokenService;

    public function setPassword(string $password): \App\Services\ApiTokenService;

    /**
     * @return mixed
     */
    public function getBearer();
}
