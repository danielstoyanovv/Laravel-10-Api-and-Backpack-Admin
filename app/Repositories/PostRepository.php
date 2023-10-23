<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\PostDTO;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PostRepository implements PostRepositoryInterface
{
    /**
     * @param PostDTO $postDTO
     * @return Post|Collection|Model|mixed
     */
    public function create(PostDTO $postDTO): mixed
    {
        return  PostFactory::new([
            'author' => $postDTO->author,
            'content' => $postDTO->content,
            'user_id' => $postDTO->user
        ])->create();
    }
}
