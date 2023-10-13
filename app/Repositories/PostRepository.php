<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Contracts\Auth\Authenticatable;

class PostRepository implements PostRepositoryInterface
{
    /**
     * @param User|Authenticatable $user
     * @return mixed
     */
    public function create(User|Authenticatable $user): mixed
    {
        return  PostFactory::new([
            'author' => request('author'),
            'content' => request('content'),
            'user_id' => $user
        ])->create();
    }
}
