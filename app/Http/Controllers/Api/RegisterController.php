<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;

class RegisterController extends Controller
{

    public function store(UserCreateRequest $request)
    {
        var_dump($request->input('name'));
        var_dump('test');
        die;

//        try {
//            $registerUserData = json_decode($request->getContent(), true);
//            if (empty($registerUserData)) {
//                throw new UnprocessableEntityHttpException(
//                    "User data is missing, please send all these fields: 'name', 'email', 'password'");
//            }
//
//            if (empty($registerUserData['name'])) {
//                throw new UnprocessableEntityHttpException(
//                    "'name' is required field");
//            }
//
//            $name = $registerUserData['name'];
//
//            if (strlen($name) > 100) {
//                throw new UnprocessableEntityHttpException(
//                    "'name' max characters are 100");
//            }
//
//            if (empty($registerUserData['email'])) {
//                throw new UnprocessableEntityHttpException(
//                    "'email' is required field");
//            }
//
//            $email = $registerUserData['email'];
//
//
//            $validator = Validator::make([
//                'email' => $email
//            ], [
//                'email' => 'email|max:255|unique:users'
//            ]);
//
//            $validator->validated();
//
//            if (empty($registerUserData['password'])) {
//                throw new UnprocessableEntityHttpException(
//                    "'password' is required field");
//            }
//
//            $password = $registerUserData['password'];
//
//            if (strlen($password) < 8) {
//                throw new UnprocessableEntityHttpException(
//                    "'password' minimum characters are 8");
//            }

//            $user = UserFactory::new([
//                'name' => $name,
//                'email' => $email,
//                'password' => Hash::make($password),
//                'status' => 'inactive'
//            ])->create();
//            return response()->json($user,ResponseAlias::HTTP_CREATED);
//        } catch (\Exception $exception) {
//            return response()->json($exception->getMessage(),ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
//        }

//      /  $user = UserFactory::new([
//            'name' => $name,
//            'email' => $email,
//            'password' => Hash::make($password),
//            'status' => 'inactive'
//        ])->create();
        //$user = User::create([
//            'name' => $request->input('name'),
//            'email' => $request->input('email'),
//            'password' => Hash::make($request->input('password')),
//            'status' => 'inactive'
//        ]);

       // return new UsersResource($user);
    }
}
