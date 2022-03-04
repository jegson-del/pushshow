<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $guarded = [];

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://pushcrowd/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }
}
