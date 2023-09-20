<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProfilesController extends Controller
{
    /**
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function index(ProfileRequest $request): JsonResponse
    {
        try {
            if ($user = User::where(['token' => $request->input('token')])->first()) {
                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
                }

                $totalLikes = 0;
                foreach ($user->posts as $post) {
                    $likedFrom = json_decode($post->liked_from, true);
                    if (!empty($likedFrom)) {
                        $totalLikes += count($likedFrom);
                    }
                }

                $data = [
                    'total_likes' => $totalLikes,
                    'total_posts' => $user->posts->count()
                ];

                return response()->json($data);

            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
