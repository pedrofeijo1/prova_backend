<?php

namespace App\Http\Middleware;

use App\Exceptions\ExpectsJsonException;
use App\Exceptions\UnauthenticatedException;
use App\Http\Services\Auth;
use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->expectsJson()) {
            throw new ExpectsJsonException;

        } else if (!$this->auth->check()) {
            throw new UnauthenticatedException;

        }

        return $next($request);
    }
}
