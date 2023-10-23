<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    /**
     * @param $userImage
     * @return mixed
     */
    public function create($userImage): mixed;
}
