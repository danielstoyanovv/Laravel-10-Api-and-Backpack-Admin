<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where([
            'email' => $request->input('email')
        ])->first();

        $cacheKey = $user->id . '_logged_in';

        if (cache()->has($cacheKey)) {
            return response()->json('User is logged in', ResponseAlias::HTTP_OK);
        }

        if(!Hash::check($request->input('password'), $user->password)) {
            return response()->json('User unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
        }
        if ($user->status === 'inactive') {
            return response()->json('User is not activated', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        cache()->put($cacheKey, true, 86400);
        return response()->json('User is logged in', ResponseAlias::HTTP_OK);
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

        $cacheKey = $user->id . '_logged_in';
        cache()->delete($cacheKey);
        return response()->json('User is logged out', ResponseAlias::HTTP_OK);
    }
}
