<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Http\Resources\UsersResource;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
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

    /**Ф
     * @param UserCreateRequest $request
     * @return UsersResource
     */
    public function store(UserCreateRequest $request): UsersResource
    {
        $usersImage = null;
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

        return new UsersResource($user);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(null, 204);
    }


    /**
     * @param UserActivateRequest $request
     * @return UsersResource|JsonResponse
     */
    public function activateUser(UserActivateRequest $request): UsersResource|JsonResponse
    {
        $activateUserData = json_decode($request->getContent(), true);

        try {
            if (!empty($activateUserData['id'])) {
                if ($user = User::find($activateUserData['id'])) {
                    $user->update([
                        'status' => 'active'
                    ]);
                }
                return new UsersResource($user);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

        }
        return response()->json('User activation issue', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
