<?php

namespace App\Interfaces;

interface ApiTokenServiceInterface
{
    /**
     * @param int $clientId `
     * @return $this`
     */
    public function setClientId(int $clientId): self;

    /**
     * @param string $clientSecret
     * @return $this
     */
    public function setClientSecret(string $clientSecret): self;

    public function setEmail(string $email): self;

    public function setPassword(string $password): self;

    /**
     * @return mixed
     */
    public function getToken(): mixed;
}
