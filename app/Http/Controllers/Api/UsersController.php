<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Resources\UsersResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;

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


    public function store(UserCreateRequest $request, UserRepository $repository)
    {
        try {
            $usersImage = $this->handleImage();
            $user = $repository->create($usersImage ?? null);
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
     * @return string|void
     */
    private function handleImage()
    {
        if (!empty(request('image_content'))) {
            $originalName = request('image_content');
            $imagesFolder = 'uploads/images';
            $imageName = pathinfo($originalName, PATHINFO_FILENAME);
            $uniqueImageName = $imageName . '-' . uniqid() . '.' . explode('.', $originalName)[1];
            $usersImage = $imagesFolder . DIRECTORY_SEPARATOR . $uniqueImageName . '.' .
                pathinfo($originalName, PATHINFO_EXTENSION);
            $fullImagePath = public_path($imagesFolder) . DIRECTORY_SEPARATOR . $uniqueImageName . '.' .
                pathinfo($originalName, PATHINFO_EXTENSION);
            file_put_contents($fullImagePath,file_get_contents($originalName));

            return $usersImage;
        }
    }

    /**
     * @param UserActivateRequest $request
     * @param ApiData $apiData
     * @return UsersResource|JsonResponse|void
     */
    public function activateUser(UserActivateRequest $request, ApiData $apiData)
    {
        try {
            if ($authUser = auth()->user()) {
                if (!$apiData->isUserAdmin($authUser->id)) {
                    return response()->json(['error' => 'You have no admin permissions'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($user = User::find(request('id'))) {
                    $user->update([
                        'status' => 'active'
                    ]);
                    return new UsersResource($user);
                }
                return response()->json(['error' => 'User not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @param UserUpdateRequest $request
     * @param ApiData $apiData
     * @return UsersResource|JsonResponse|void
     */
    public function userUpdate(UserUpdateRequest $request, ApiData $apiData)
    {
        try {
            if ($user = auth()->user()) {
                if (!$apiData->isUserActive($user)) {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }
                $user->update([
                    'name' => $request->input('name'),
                    'short_description' => $request->input('short_description')
                ]);
                return new UsersResource($user);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);        }
    }
}
