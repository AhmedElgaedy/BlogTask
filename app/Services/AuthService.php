<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InvalidCredentialsException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->authRepository->create($data);
    }

    public function login(array $credentials): string
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        return $token;
    }

    public function getUserFromToken(string $token): User
    {
        return JWTAuth::authenticate($token);
    }

}