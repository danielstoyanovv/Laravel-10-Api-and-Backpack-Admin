<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserRepositoryInterfaces;
use App\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterfaces $repository) {}

    /**
     * @param $userImage
     * @return mixed
     */
    public function create($userImage): mixed
    {
        return $this->repository->create($userImage);
    }
}
