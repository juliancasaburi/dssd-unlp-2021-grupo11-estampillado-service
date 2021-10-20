<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @OA\Post(
     *    path="/api/auth/login",
     *    summary="Login",
     *    description="Login con email y password",
     *    operationId="authLogin",
     *    tags={"auth"},
     *    @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *             type="object", 
     *             @OA\Property(
     *                property="email",
     *                type="string",
     *             ),
     *             @OA\Property(
     *                property="password",
     *                type="string",
     *             ),
     *          ),
     *      )
     *    ),
     *    @OA\Response(
     *       response=200,
     *       description="Succesful login",
     *       @OA\JsonContent(
     *          example=""
     *       )
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="401 Unauthorized",
     *       @OA\JsonContent(
     *          example={"error":"Unauthorized"}
     *       )
     *    ),
     *    @OA\Response(
     *       response=500,
     *       description="500 internal server error",
     *       @OA\JsonContent(
     *          example="500 internal server error"
     *       )
     *    ),
     * )
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = auth('api')->attempt($credentials);

        if (!$token){ // hashed password
            $user = DB::table('users')
                ->where('email', $credentials['email'])
                ->select('id', 'password')
                ->first();

            if ($user and $user->password == $credentials['password'])
                $token = auth('api')->tokenById($user->id);
            else
                return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @OA\Post(
     *    path="/api/auth/logout",
     *    summary="Logout",
     *    description="Logout",
     *    operationId="authLogout",
     *    tags={"auth"},
     *    security={{ "apiAuth": {} }},
     *    @OA\Response(
     *       response=200,
     *       description="Success"
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="Unauthorized"
     *    ),
     *    @OA\Response(
     *       response=500,
     *       description="500 internal server error",
     *       @OA\JsonContent(
     *          example="500 internal server error"
     *       )
     *    ),
     * )
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(["auth" => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]]);
    }
}
