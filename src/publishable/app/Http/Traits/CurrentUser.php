<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth as IlluminateAuth;

trait CurrentUser
{
    public function user()
    {
        $user = IlluminateAuth::user();

        if (!$user) {
            echo json_encode([
                'error' => 'You must be authorized.',
            ]);
            exit;
        }

        return $user;
    }
}