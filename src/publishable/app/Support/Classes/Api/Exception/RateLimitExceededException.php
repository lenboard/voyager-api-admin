<?php

namespace App\Support\Classes\Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RateLimitExceededException extends HttpException {}
