<?php

namespace App\Interfaces;

use App\Models\User;

interface PostRepositoryInterface
{
    /**
     * @param User $user
     * @return mixed
     */
    public function create(User $user): mixed;
}
