<?php

namespace App\Repositories;

use App\Models\User;
use Database\Factories\PostFactory;

class PostRepository
{
    /**
     * @param User $user
     * @return mixed
     */
    public function create(User $user): mixed
    {
        return  PostFactory::new([
            'author' => request('author'),
            'content' => request('content'),
            'user_id' => $user
        ])->create();
    }
}
