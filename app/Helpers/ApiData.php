<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class ApiData
{
    /**
     * @return bool
     */
    public function checkUserCredentials(): bool
    {
        return Auth::attempt(['email' => request('email'), 'password' => request('password')]);
    }

    /**
     * @param User|Authenticatable $user
     * @return bool
     */
    public function isUserActive(User|Authenticatable $user): bool
    {
        return $user->status === 'active';
    }

    /**
     * @param string $id
     * @return bool
     */
    public function isUserAdmin(string $id): bool
    {
        $user = User::find($id);
        return $user->is_admin === 1;
    }

    /**
     * @param ClientRepository $clientRepository
     * @param User $user
     * @return Client|mixed
     */
    public function getOrCreatePassportClient(ClientRepository $clientRepository, User $user): mixed
    {
        if (!empty($clientRepository->forUser($user->id))) {
            $client = $clientRepository->forUser($user->id)->first();
        } else {
            $client = $clientRepository->create(
                $user->id, $user->name, '', null, false, true
            );
        }
        return $client;
    }
}
