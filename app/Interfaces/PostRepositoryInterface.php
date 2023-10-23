<?php

namespace App\Interfaces;

use App\DTO\PostDTO;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PostRepositoryInterface
{
    /**
     * @param PostDTO $postDTO
     * @return Post|Collection|Model|mixed
     */
    public function create(PostDTO $postDTO): mixed;
}
