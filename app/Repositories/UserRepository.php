<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterfaces;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterfaces
{
    /**
     * @return User|Collection|Model|mixed
     */
    public function create($userImage): mixed
    {
        return UserFactory::new([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password'),
            'status' => 'inactive',
            'image' => $userImage,
            'short_description' => request('short_description')
        ])->create();
    }
}
