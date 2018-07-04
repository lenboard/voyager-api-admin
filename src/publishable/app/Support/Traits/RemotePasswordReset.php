<?php

namespace App\Support\Traits;

trait RemotePasswordReset
{
    use UsesApi;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->api()
            ->passwordResetNotification()
            ->post([
                'token' => $token,
                'email' => $this->email,
            ]);
    }
}
