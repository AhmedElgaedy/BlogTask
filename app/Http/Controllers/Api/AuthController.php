<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->authService->register($data);
        

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        try {
            $token = $this->authService->login($credentials);
        } catch (UnauthorizedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return response()->json(['token' => $token]);
    
    }
    
    public function me(Request $request)
    {
        $token = $request->bearerToken();

        try {
            $user = $this->authService->getUserFromToken($token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return response()->json(['user' => $user]);
    }
  
}