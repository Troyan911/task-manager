<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthRequest $request)
    {
        $data = $request->validated();

        if (! auth()->attempt($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials',
            ], 422);
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
