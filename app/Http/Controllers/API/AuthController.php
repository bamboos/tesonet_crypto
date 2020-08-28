<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 * path="/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="User login successfully",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="User login successfully"),
 *       @OA\Property(property="data", type="object", example="Token and username")
 *     )
 * ),
 * @OA\Response(
 *    response=404,
 *    description="Unauthorised",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthorised")
 *    )
 * )
 * ),
 */

class AuthController extends BaseController
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
