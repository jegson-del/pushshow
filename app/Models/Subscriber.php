<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Traits\RefGenerator;

class Subscriber extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, RefGenerator;

    protected $refPrefix = 'PCS-';

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->reference = $model->generateReference();
        });
    }

    /**
     * 
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
