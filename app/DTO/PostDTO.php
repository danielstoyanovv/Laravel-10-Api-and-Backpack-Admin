<?php

namespace App\DTO;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PostDTO
{
    public function __construct(public string $author, public string $content, public User|Authenticatable $user)
    {
    }
}
