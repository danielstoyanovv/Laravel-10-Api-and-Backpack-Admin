<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UserDTO;
use App\Interfaces\UserRepositoryInterfaces;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterfaces $repository) {}

    /**
     * @param UserDTO $userDTO
     * @return User|Collection|Model|mixed
     */
    public function create(UserDTO $userDTO): mixed
    {
        return $this->repository->create($userDTO);
    }
}
