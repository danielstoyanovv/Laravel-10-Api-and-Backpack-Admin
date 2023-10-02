<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenUpdateRequest;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Resources\UsersResource;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Http;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UsersResource::collection(User::where(
            'name', '!=', 'Admin'
        )->get());
    }


    public function store(UserCreateRequest $request)
    {
        try {
            if (!empty($request->input('image_content'))) {
                $originalName = $request->input('image_content');
                $imagesFolder = 'uploads/images';
                $imageName = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueImageName = $imageName . '-' . uniqid() . '.' . explode('.', $originalName)[1];
                $usersImage = $imagesFolder . DIRECTORY_SEPARATOR . $uniqueImageName . '.' .
                    pathinfo($originalName, PATHINFO_EXTENSION);
                $fullImagePath = public_path($imagesFolder) . DIRECTORY_SEPARATOR . $uniqueImageName . '.' .
                    pathinfo($originalName, PATHINFO_EXTENSION);
                file_put_contents($fullImagePath,file_get_contents($originalName));
            }

            $user = UserFactory::new([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => 'inactive',
                'image' => $usersImage ?? null,
                'short_description' => $request->input('short_description')
            ])->create();

            $accessToken = $user->createToken('MyApp')->accessToken;
            $success['token'] = $accessToken;
            $success['name'] =  $user->name;
            return new UsersResource($user);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param UserActivateRequest $request
     * @return UsersResource|JsonResponse
     */
    public function activateUser(UserActivateRequest $request)
    {
        try {
            if ($userByToken = User::where(['token' => $request->input('token')])->first()) {
                $userRoles = json_decode($userByToken->roles, true);
                if (!in_array('Admin', $userRoles)) {
                    throw new UnprocessableEntityHttpException('Only admin users can activate users');
                }

                if ($user = User::find($request->input('id'))) {
                    $user->update([
                        'status' => 'active'
                    ]);
                }
                return new UsersResource($user);
            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param TokenUpdateRequest $request
     * @return UsersResource|JsonResponse
     */
    public function tokenUpdate(TokenUpdateRequest $request): UsersResource|JsonResponse
    {
        try {
            if ($user = User::where(['refresh_token' => $request->input('refresh_token')])->first()) {
                $user->update([
                    'token' => TokenGenerator::generate(),
                    'expires_at' => Carbon::now()->addDay()
                ]);
                return new UsersResource($user);
            }
            throw new UnprocessableEntityHttpException('Token not found');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * @param UserUpdateRequest $request
     * @return UsersResource|JsonResponse
     */
    public function userUpdate(UserUpdateRequest $request): UsersResource|JsonResponse
    {
        try {
            if ($user = User::where(['token' => $request->input('token')])->first()) {
                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
                }

                $user->update([
                    'name' => $request->input('name'),
                    'short_description' => $request->input('short_description')
                ]);
                return new UsersResource($user);
            }
            throw new UnprocessableEntityHttpException('Token not found');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
