<?php

namespace App\Repositories;

use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository
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
