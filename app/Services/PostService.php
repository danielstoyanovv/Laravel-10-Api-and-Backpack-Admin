<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\PostDTO;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\PostServiceInterface;

class PostService implements PostServiceInterface
{
    public function __construct(private PostRepositoryInterface $repository) {}

    /**
     * @param PostDTO $postDTO
     * @return mixed
     */
    public function create(PostDTO $postDTO): mixed
    {
        return $this->repository->create($postDTO);
    }
}
