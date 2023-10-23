<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\PostServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PostService implements PostServiceInterface
{
    public function __construct(private PostRepositoryInterface $repository) {}

    /**
     * @param User|Authenticatable $user
     * @return mixed
     */
    public function create(User|Authenticatable $user): mixed
    {
        return $this->repository->create($user);
    }
}
