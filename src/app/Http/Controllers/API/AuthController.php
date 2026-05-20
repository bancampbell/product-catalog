<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authService->authenticate(
            $request->email,
            $request->password
        );

        $token = $this->authService->createToken($user);

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return $this->success(null, 'Logged out', Response::HTTP_NO_CONTENT);
    }
}
