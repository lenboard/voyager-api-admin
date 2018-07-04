<?php

namespace App\Support\Middleware;

use Closure;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Support\Classes\Api\Auth\Auth as AuthProvider;

class Scope
{
    use HandlesAuthorization;

    /**
     * Dingo auth instance.
     *
     * @var \App\Support\Classes\Api\Auth\Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \App\Support\Classes\Api\Auth\Auth  $auth
     * @return void
     */
    public function __construct(AuthProvider $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $scopes
     * @return mixed
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $this->authorize(...$scopes);

        return $next($request);
    }

    /**
     * Authorize request.
     *
     * @param  mixed $scopes
     * @return \Illuminate\Http\Response
     */
    protected function authorize(...$scopes)
    {
        $result = $this->check(...$scopes);

        return ($result) ? $this->allow() : $this->deny();
    }

    /**
     * Check users scopes.
     *
     * @param  mixed $scopes
     * @return boolean
     */
    protected function check(...$scopes)
    {
        return $this->auth->hasScope(...$scopes);
    }
}
