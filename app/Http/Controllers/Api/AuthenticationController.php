<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class AuthenticationController extends Controller
{
    public function __construct(private ApiData $apiData)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request):  JsonResponse
    {
        if ($user = User::where([
            'email' => $request->input('email')
        ])->first()) {
            if (!$this->apiData->isUserActive($user)) {
                return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $token = auth()->user()->createToken('MyApp')->accessToken;
            return response()->json(['success' => 'logged in'], ResponseAlias::HTTP_OK);
        }

        return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function logout(LoginRequest $request): JsonResponse
    {

        if ($user = User::where([
            'email' => $request->input('email')
        ])->first()) {
            if (!$this->apiData->isUserActive($user)) {
                return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        Auth::logout();
        Session::flush();
        return response()->json(['success' => 'logged out'], ResponseAlias::HTTP_OK);
    }
}
