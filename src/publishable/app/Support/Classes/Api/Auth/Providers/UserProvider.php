<?php

namespace App\Support\Classes\Api\Auth\Providers;

use Exception;
use Illuminate\Auth\EloquentUserProvider;
use App\Support\Classes\Api\Api as ApiProvider;
use App\Support\Classes\Api\Exception\AuthException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class UserProvider extends EloquentUserProvider
{
    /**
     * API client instance.
     *
     * @var \App\Support\Classes\Api\Api $api
     */
    protected $api;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @param  \App\Support\Classes\Api\Api  $api
     * @return void
     */
    public function __construct(HasherContract $hasher, ApiProvider $api, $model)
    {
        parent::__construct($hasher, $model);

        $this->api = $api;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = parent::retrieveByCredentials($credentials);

        if ($user) {
            return $user;
        }

        try {
            $login = $this->api->login()->post($credentials);

            $user = $this->createModel();
            $user->forceFill([
                'email'     => $login->data()->email,
                'login'     => $login->data()->login,
                'api_token' => encrypt($login->data()->token),
            ])->save();

            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        try {
            $user = $this->api->login()->post($credentials);

            if (isset($user->data()->token)) {
                return true;
            }
        } catch (AuthException $e) {
            return false;
        }
    }
}
