<?php

namespace App\Interfaces;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface
{
    /**
     * @param UserDTO $userDTO
     * @return User|Collection|Model|mixed
     */
    public function create(UserDTO $userDTO): mixed;
}
