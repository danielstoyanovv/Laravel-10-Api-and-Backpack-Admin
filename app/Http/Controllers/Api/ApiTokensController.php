<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiTokenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\ClientRepository;

class ApiTokensController extends Controller
{
    /**
     * @param ApiTokenRequest $request
     * @param ClientRepository $clientRepository
     * @return JsonResponse
     */
    public function token(ApiTokenRequest $request, ClientRepository $clientRepository): JsonResponse
    {
        try {
            if (!Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
            }

            if ($user = User::where(['email' => $request->input('email')])->first()) {

                 if ($user->status === 'inactive') {
                     return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                 }


                $newClient = $clientRepository->create(
                    $user->id, $user->name, '', null, false, true
                );
                $responseToken = Http::timeout(9999999)->post('http://127.0.0.1:8000/oauth/token', [
                    "grant_type" => "password",
                    "client_id" => $newClient->id,
                    "client_secret" => $newClient->secret,
                    "username" => $user->email,
                    "password" => request('password'),
                    "scope" => "",
                ]);
                $tokenResult = json_decode($responseToken->body(), true);
                $success['success'] = true;
                $success['token'] = $tokenResult['access_token'];
                return response()->json([$success], ResponseAlias::HTTP_OK);
            }
            return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

}
