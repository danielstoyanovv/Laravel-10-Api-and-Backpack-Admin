<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterfaces
{
    /**
     * @return User|Collection|Model|mixed
     */
    public function create($userImage): mixed;
}
