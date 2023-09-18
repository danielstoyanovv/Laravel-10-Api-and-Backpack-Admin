<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return UsersResource|JsonResponse
     */
    public function login(LoginRequest $request): UsersResource|JsonResponse
    {
        $user = User::where([
            'email' => $request->input('email')
        ])->first();

        if ($user->expires_at < Carbon::now()) {
            return response()->json('Token is expired', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        if(!Hash::check($request->input('password'), $user->password)) {
            return response()->json('User unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }
        if ($user->status === 'inactive') {
            return response()->json('User is not activated', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UsersResource($user);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function logout(LoginRequest $request): JsonResponse
    {
        $user = User::where([
            'email' => $request->input('email')
        ])->first();

        if(!Hash::check($request->input('password'), $user->password)) {
            return response()->json('User unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }
        if ($user->status === 'inactive') {
            return response()->json('User is not activated', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json('User is logged out', ResponseAlias::HTTP_OK);
    }
}
