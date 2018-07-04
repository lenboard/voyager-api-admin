<?php

namespace App\Support\Classes\Api\Auth;

use Carbon\Carbon;
use App\Support\Classes\Api\Api;
use Illuminate\Support\Facades\Auth as AuthProvider;
use App\Support\Classes\Api\Exception\AuthException;

class Auth
{
    /**
     * API client instance.
     *
     * @var \App\Support\Classes\Api\Api
     */
    protected $api;

    /**
     * API user instance.
     *
     * 
     */
    protected static $user = null;

    /**
     * Set API client instance.
     *
     * @param  \App\Support\Classes\Api\Api
     * @return static
     */
    public function setClient(Api $api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     * Check user scope with exception on failure.
     *
     * @param  mixed $scopes
     * @return bool
     */
    public function checkScope(...$scopes)
    {
        if ($this->hasScope(...$scopes)) {
            return true;
        }

        throw new AuthException('You are not authorized to use this.');
    }

    /**
     * Check user groups.
     *
     * @param  mixed $scopes
     * @return bool
     */
    public function hasGroup(...$groups)
    {
        // persist scopes across function calls
        static $availableGroups = null;

        if ($availableGroups === null) {
            $availableGroups = (array)$this->user()->scope_aliases;
        }

        $groups = array_wrap($groups);

        foreach ($groups as $group) {
            if (in_array($group, $availableGroups)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check user scope.
     *
     * @param  mixed $scopes
     * @return bool
     */
    public function hasScope(...$scopes)
    {
        // persist scopes across function calls
        static $availableScopes = null;

        if ($availableScopes === null) {
            $availableScopes = (array)$this->user()->scope;
        }

        $scopes = array_wrap($scopes);

        // allow all scopes for admin alias
        if (in_array('admin', $availableScopes)) {
            return true;
        }

        foreach ($scopes as $scope) {
            if (in_array($scope, $availableScopes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user.
     *
     * @param  mixed $scopes
     * @return bool
     */
    public function user()
    {
        if (!AuthProvider::check()) {
            return null;
        }

        if (self::$user === null) {
            $response = $this->api->profile()->with('user_information')->json();

            if($response->success){
                self::$user = $response->data;
                self::$user->information = $response->user_information->data;
            }
            /*$attributes = $this->api
                ->profile()
                ->with('information')
                ->get()
                ->toArray();*/

            //self::$user = clone AuthProvider::user();
            //self::$user->forceFill((array) $user);
        }

        return self::$user;
    }

    /**
     * Create authorization header.
     *
     * @return array
     */
    public function header()
    {
        throw new \Exception($this->secret());

        $secret = $this->secret();
        return ($secret === null ? [] : ['Authorization' => 'mac '.$secret]);
    }

    /**
     * Create time-based secret with time-to-live.
     *
     * @return array
     */
    public function secretWithTTL()
    {
        static $secret = null;

        if ($secret === null && AuthProvider::check()) {
            $loginType = $this->loginType();

            $identity = AuthProvider::user()->$loginType;
            $token = decrypt(AuthProvider::user()->api_token);

            $time = floor(Carbon::now('UTC')->timestamp / 30);
            $digest = hash_hmac('sha256', $time, $token, true);

            $secret = [bin2hex($identity.':'.$digest), $time * 30 + 30];
        }

        return $secret ?: [null, 0];
    }

    /**
     * Get time-based secret.
     *
     * @return string|null
     */
    public function secret()
    {
        return $this->secretWithTTL()[0];
    }

    /**
     * Get secret time-to-live.
     *
     * @return integer
     */
    public function secretTTL()
    {
        return $this->secretWithTTL()[1];
    }

    /**
     * Get login type.
     *
     * @return string
     */
    public function loginType()
    {
        return $this->api->loginType()->data()->login_type;
    }

    /**
     * Register the routes for authentication.
     *
     * @param  array|null  $attributes
     * @return void
     */
    public function routes(array $attributes = null)
    {
        if (app()->routesAreCached()) {
            return;
        }

        $attributes = $attributes ?: ['middleware' => ['web', 'auth']];

        app('router')->group($attributes, function ($router) {
            $router->get('/auth/mac', AuthController::class.'@mac');
        });
    }
}
