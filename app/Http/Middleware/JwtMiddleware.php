<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;


    class JwtMiddleware extends BaseMiddleware
    {
        public function handle(Request $request, Closure $next)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                    return response()->json(['error' => 'Invalid token'], 401);
                } else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json(['error' => 'Token expired'], 401);
                } else {
                    return response()->json(['error' => 'Token not found'], 401);
                }
            }
            return $next($request);
        }
    }