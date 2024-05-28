<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @OA\Post(
     *      path="/api/auth",
     *      operationId="auth",
     *      tags={"Authentication"},
     *      summary="Authenticate user",
     *      description="Authenticate a user using email and password",
     *
     *      @OA\RequestBody(
     *          required=true,
     *          description="User credentials",
     *
     *          @OA\JsonContent(
     *              required={"email","password"},
     *
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful login",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *          )
     *      )
     *  )
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        if (! $request->validated() || ! auth()->attempt($request->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $token = auth()->user()->createToken(
            $request->device_name ?? 'api',
            ['full'],
            now()->addHours(24)
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token->plainTextToken,
            ],
        ]);
    }
}
