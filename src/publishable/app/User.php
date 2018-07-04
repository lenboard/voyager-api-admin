<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Support\Traits\RemotePasswordReset;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable, CanResetPassword, RemotePasswordReset {
        RemotePasswordReset::sendPasswordResetNotification insteadof CanResetPassword;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'login',
        'api_token',
        'api_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
        'api_id',
        'password',
    ];


    /**
     * Generate random password.
     *
     * @return string
     */
    public static function generatePassword()
    {
        return str_random(32);
    }

    /**
     * Sets password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes[ 'password' ] = bcrypt($password);
    }

    /**
     * Get API id.
     *
     * @return integer
     */
    public function getApiId()
    {
        return decrypt($this->api_id);
    }
}
