<?php

namespace App\Http\Middleware;

use App\Acme\Traint\ApiResponser;
use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomThrottleMiddleware extends ThrottleRequests
{
    use ApiResponser;
    
    public function handle($request, Closure $next, $maxAttempts = 10, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            // throw $this->buildException($key, $maxAttempts);
            return $this->errorRespond('too many attempts', 429);
        }

        $this->limiter->hit($key, $decayMinutes);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
}
