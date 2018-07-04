<?php

namespace App\Support\Classes\Api\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthException extends UnauthorizedHttpException
{
    /**
     * Exception contructor.
     *
     * @param  mixed  $parameters
     * @return void
     */
    public function __construct(...$parameters)
    {
        parent::__construct(null, ...$parameters);
    }
}
