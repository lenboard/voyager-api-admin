<?php

namespace App\Support\Classes\Api\Exception;

use Exception;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception {
    /**
     * Message bag.
     *
     * @var \Illuminate\Support\MessageBag
     */
    public $errors;

    /**
     * Create a new validation exception instance.
     *
     * @param  array $errors
     * @return void
     */
     public function __construct(array $errors = [])
     {
         $this->errors = new MessageBag($errors);
     }
}
