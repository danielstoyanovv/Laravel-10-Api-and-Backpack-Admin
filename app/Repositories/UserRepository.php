<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Interfaces\UserRepositoryInterfaces;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterfaces
{
    /**
     * @param UserDTO $userDTO
     * @return User|Collection|Model|mixed
     */
    public function create(UserDTO $userDTO): mixed
    {
        return UserFactory::new([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => $userDTO->password,
            'status' => $userDTO->status,
            'image' => $userDTO->image,
            'short_description' => $userDTO->shortDescription
        ])->create();
    }
}
