<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletedAtClearRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostLikeRequest;
use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class PostsController extends Controller
{
    /**
     * @param PostCreateRequest $request
     * @return PostsResource|JsonResponse
     */
    public function store(PostCreateRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = User::where(['token' => $request->input('token')])->first()) {

                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
                }

                $post = PostFactory::new([
                    'author' => $request->input('author'),
                    'content' => $request->input('content'),
                    'user_id' => $user
                ])->create();
                return new PostsResource($post);
            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param PostLikeRequest $request
     * @return PostsResource|JsonResponse
     */
    public function postLike(PostLikeRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = User::where(['token' => $request->input('token')])->first()) {

                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
                }

                if ($post = Post::find($request->input('id'))) {

                    $cuffentLikedFrom = !empty($post->liked_from) ? json_decode($post->liked_from, true) : [];

                    $newLike = [$user->id];
                    $post->update([
                        'liked_from' => json_encode(array_merge($newLike, $cuffentLikedFrom), true)
                    ]);
                    return new PostsResource($post);
                }
                throw new UnprocessableEntityHttpException('Post not found');
            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param PostLikeRequest $request
     * @return PostsResource|JsonResponse
     */
    public function postUnLike(PostLikeRequest $request): PostsResource|JsonResponse
    {
        try {
            if ($user = User::where(['token' => $request->input('token')])->first()) {
                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
                }
                if ($post = Post::find($request->input('id'))) {
                    $cuffentLikedFrom = !empty($post->liked_from) ? json_decode($post->liked_from, true) : [];
                    if (!empty($cuffentLikedFrom)) {
                        $key = array_search($user->id, $cuffentLikedFrom);
                        unset($cuffentLikedFrom[$key]);
                    }
                    $post->update([
                        'liked_from' => json_encode(array_merge($cuffentLikedFrom), true)
                    ]);
                    return new PostsResource($post);
                }
                throw new UnprocessableEntityHttpException('Post not found');
            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(Post $post)
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
            if ($user = User::where(['token' => $request->input('token')])->first()) {
                if ($user->status === 'inactive') {
                    throw new UnprocessableEntityHttpException('User is not activated');
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
                throw new UnprocessableEntityHttpException('Post not found');
            }
            throw new UnprocessableEntityHttpException('Token not found');

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }



}
