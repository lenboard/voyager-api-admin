<?php

namespace App\Support\Classes\Api;

use App\Support\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CaptchaController extends Controller
{
    /**
     * Get captcha image.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCaptcha()
    {
        return Api::captcha()->create();
    }
}
