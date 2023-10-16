<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\ApiData;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeletedAtClearRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostLikeRequest;
use App\Http\Resources\PostsResource;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PostsController extends Controller
{
    public function __construct(private ApiData $apiData)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse|LengthAwarePaginator
     */
    public function index(Request $request): JsonResponse|LengthAwarePaginator
    {
        if ($user = auth()->user()) {
            if (!$this->apiData->isUserActive($user)) {
                return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
            return Post::orderBy('id', 'DESC')->paginate(20);
        }
        return response()->json(['error' => 'User is not logged in'], ResponseAlias::HTTP_UNAUTHORIZED);

    }

    /**
     * @param PostCreateRequest $request
     * @param PostRepositoryInterface $repository
     * @return PostsResource|JsonResponse
     */
    public function store(PostCreateRequest $request, PostRepositoryInterface $repository): PostsResource|JsonResponse
    {
        try {
            if ($user = auth()->user()) {
                if (!$this->apiData->isUserActive($user)) {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }
                $post = $repository->create($user);
                return new PostsResource($post);
            }
            return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param PostLikeRequest $request
     * @return PostsResource|JsonResponse
     */
    public function postLike(PostLikeRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = auth()->user()) {
                if (!$this->apiData->isUserActive($user)) {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }
                if ($post = Post::find($request->input('id'))) {

                    $cuffentLikedFrom = !empty($post->liked_from) ? json_decode($post->liked_from, true) : [];

                    $newLike = [$user->id];
                    $post->update([
                        'liked_from' => json_encode(array_merge($newLike, $cuffentLikedFrom))
                    ]);
                    return new PostsResource($post);
                }
                return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param PostLikeRequest $request
     * @return PostsResource|JsonResponse
     */
    public function postUnLike(PostLikeRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = auth()->user()) {
                if (!$this->apiData->isUserActive($user)) {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }
                if ($post = Post::find($request->input('id'))) {
                    $cuffentLikedFrom = !empty($post->liked_from) ? json_decode($post->liked_from, true) : [];
                    if (!empty($cuffentLikedFrom)) {
                        $key = array_search($user->id, $cuffentLikedFrom);
                        unset($cuffentLikedFrom[$key]);
                    }
                    $post->update([
                        'liked_from' => json_encode(array_merge($cuffentLikedFrom))
                    ]);
                    return new PostsResource($post);
                }
                return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);        }
    }

    /**
     * @param Post $post
     * @return Application|ResponseFactory|Response
     */
    public function destroy(Post $post): Application|ResponseFactory|Response
    {
        $post->delete();
        return response(null, 204);
    }

    /**
     * @param DeletedAtClearRequest $request
     * @return PostsResource|JsonResponse
     */
    public function deletedAtClear(DeletedAtClearRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = auth()->user()) {
                if (!$this->apiData->isUserActive($user)) {
                    return response()->json(['error' => 'User is inactive'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
                }
                if ($post = DB::table('posts')->select('*')
                    ->where('id','=',$request->input('id'))
                    ->first()) {
                    Db::update(sprintf(
                        "Update posts set deleted_at = null where id=%d",
                        $request->input('id')
                    ));
                   $post = Post::find($request->input('id'));
                   return new PostsResource($post);
                }
                return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);
            }
            return response()->json(['error' => 'not found'], ResponseAlias::HTTP_NOT_FOUND);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['error' => 'Something happened'], ResponseAlias::HTTP_BAD_REQUEST);         }
    }
}
