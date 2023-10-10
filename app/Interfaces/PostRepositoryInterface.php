<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface PostRepositoryInterface
{
    /**
     * @param User|Authenticatable $user
     * @return mixed
     */
    public function create(User|Authenticatable $user): mixed;
}
