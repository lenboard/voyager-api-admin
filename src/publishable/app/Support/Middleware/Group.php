<?php

namespace App\Support\Middleware;

use Closure;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Support\Classes\Api\Auth\Auth as AuthProvider;

class Group
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
     * @param  mixed  $groups
     * @return mixed
     */
    public function handle($request, Closure $next, ...$groups)
    {
        $this->authorize(...$groups);

        return $next($request);
    }

    /**
     * Authorize request.
     *
     * @param  mixed $groups
     * @return \Illuminate\Http\Response
     */
    protected function authorize(...$groups)
    {
        $result = $this->check(...$groups);

        return ($result) ? $this->allow() : $this->deny();
    }

    /**
     * Check users groups.
     *
     * @param  mixed $groups
     * @return boolean
     */
    protected function check(...$groups)
    {
        return $this->auth->hasGroup(...$groups);
    }
}
