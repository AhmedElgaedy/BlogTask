<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiting\Unlimited;

class RateLimitMiddleware
{
    private $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next, $maxAttempts = 5, $decayMinutes = 1)
    {
        if ($this->limiter->tooManyAttempts($this->resolveRequestSignature($request), $maxAttempts)) {
            throw new ThrottleRequestsException('Too Many Attempts.');
        }

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($request, $maxAttempts)
        );
    }

    protected function calculateRemainingAttempts($request, $maxAttempts)
    {
        if ($this->limiter->limiter()->attempts($this->resolveRequestSignature($request)) === Unlimited::attempts()) {
            return Unlimited::remainingAttempts();
        }

        return $maxAttempts - $this->limiter->limiter()->attempts($this->resolveRequestSignature($request)) + 1;
    }

    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}