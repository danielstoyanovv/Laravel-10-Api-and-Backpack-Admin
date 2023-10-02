<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        var_dump(auth()->user()->id);
        die;
        if ($user = auth()->user()) {
                if ($user->status === 'inactive') {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }

                $totalLikes = 0;
                foreach ($user->posts as $post) {
                    $likedFrom = json_decode($post->liked_from, true);
                    if (!empty($likedFrom)) {
                        $totalLikes += count($likedFrom);
                    }
                }
                $data = [
                    'user' => [
                        'total_likes' => $totalLikes,
                        'total_posts' => $user->posts->count()
                ]];
                return response()->json($data);
            }
            return response()->json(['error' => 'User is not logged in'], ResponseAlias::HTTP_UNAUTHORIZED);
    }
}
