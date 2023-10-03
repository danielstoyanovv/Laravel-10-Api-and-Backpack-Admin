<?php

namespace App\Repositories;

use Database\Factories\PostFactory;

class PostRepository
{
    /**
     * @param $user
     * @return mixed
     */
    public function create($user): mixed
    {
        return  PostFactory::new([
            'author' => request('author'),
            'content' => request('content'),
            'user_id' => $user
        ])->create();
    }
}
