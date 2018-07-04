<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Api extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return \App\Support\Classes\Api\Api
     */
    protected static function getFacadeAccessor()
    {
        return 'api.client';
    }
}
