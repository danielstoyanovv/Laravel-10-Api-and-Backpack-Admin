<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(public string $name, public string $email, public string $password,
                                public string $status, public $image, public string $shortDescription)
    {}
}
