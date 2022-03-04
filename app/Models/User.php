<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\EmailVerification;
use App\Notifications\ResetPasswordNotification;
use App\Traits\RefGenerator;
use App\Models\BadgeNegative;
use App\Models\BadgePositive;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, RefGenerator;

    protected $guarded = [];

    protected $refPrefix = 'PCU-';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->reference = $model->generateReference();
        });
    }

    public function sendEmailVerificationNotification()
    {
       $this->notify(new EmailVerification($this->username, $this->email));
    }

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://pushcrowd/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }

    public function badgeNegative()
    {
        return $this->hasOne(BadgeNegative::class, 'id', 'badgeNegative_id');
    }

    public function badgePositive()
    {
        return $this->hasOne(BadgePositive::class, 'id', 'badgePositive_id');
    }
}
