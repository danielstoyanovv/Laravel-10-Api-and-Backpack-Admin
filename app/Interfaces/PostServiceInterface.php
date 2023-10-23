<?php

namespace App\Interfaces;

use App\DTO\PostDTO;

interface PostServiceInterface
{
    /**
     * @param PostDTO $postDTO
     * @return mixed
     */
    public function create(PostDTO $postDTO): mixed;
}
