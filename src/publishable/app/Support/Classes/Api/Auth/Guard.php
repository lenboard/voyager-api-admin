<?php

namespace App\Support\Classes\Api\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use App\Support\Classes\Api\Api as ApiProvider;

class Guard extends SessionGuard
{
    /**
     * API client instance.
     *
     * @var \App\Support\Classes\Api\Api $api
     */
    protected $api;

    /**
     * Create a new authentication guard.
     *
     * @param  string  $name
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @param  \App\Support\Classes\Api\Api  $api
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return void
     */
    public function __construct($name,
                                UserProvider $provider,
                                Session $session,
                                ApiProvider $api,
                                Request $request = null)
    {
        parent::__construct($name, $provider, $session, $request);

        $this->api = $api;
    }
}
