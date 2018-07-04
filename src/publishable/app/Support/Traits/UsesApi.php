<?php

namespace App\Support\Traits;

trait UsesApi
{
    /**
     * Get api request builder.
     *
     * @return string
     */
    public function api()
    {
        return app('api.client');
    }
}
