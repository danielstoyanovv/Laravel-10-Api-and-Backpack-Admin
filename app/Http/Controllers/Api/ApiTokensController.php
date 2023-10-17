<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\ApiData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiTokenRequest;
use App\Models\User;
use App\Interfaces\ApiTokenServiceInterface;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\App;

class ApiTokensController extends Controller
{
    /**
     * @param ApiTokenRequest $request
     * @param ClientRepository $clientRepository
     * @param ApiData $apiData
     * @return JsonResponse
     */
    public function token(ApiTokenRequest $request, ClientRepository $clientRepository, ApiData $apiData): JsonResponse
    {
        try {
            if (!$apiData->checkUserCredentials()) {
                return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
            if ($user = User::where(['email' => $request->input('email')])->first()) {
                 if (!$apiData->isUserActive($user)) {
                     return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                 }
                 $client = $apiData->getOrCreatePassportClient($clientRepository, $user);
                 $apiTokenService = App::make(ApiTokenServiceInterface::class);
                 $tokenResult = $apiTokenService
                     ->setClientId($client->id)
                     ->setClientSecret($client->secret)
                     ->setEmail($user->email)
                     ->setPassword(request('password'))
                     ->getToken();
                return response()->json([
                    'success' => 1,
                    'token' => $tokenResult['access_token']
                ], ResponseAlias::HTTP_OK);
            }
            return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

}
