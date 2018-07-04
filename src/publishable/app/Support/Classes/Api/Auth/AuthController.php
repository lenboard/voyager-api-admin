<?php

namespace App\Support\Classes\Api\Auth;

use App\Support\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    /**
     * Get mac header.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mac(Request $request)
    {
        return response()->json([
            'token' => Api::auth()->secret(),
            'type'  => 'mac',
            'ttl'   => Api::auth()->secretTTL(),
        ])->withHeaders(Api::auth()->header());
    }
}
