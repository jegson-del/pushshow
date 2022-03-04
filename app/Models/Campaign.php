<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RefGenerator;

class Campaign extends Model
{
    use HasFactory, RefGenerator;

    protected $guarded = [];
    
    protected $refPrefix = 'PCP-';

    // protected $facebookPrefix = 'facebook-';

    // protected $instagramPrefix = 'instagram-';

    // protected $youtubePrefix = 'youtube-';

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->reference = $model->generateReference();
            // $model->facebook_ref = $model->generateReference($model, $facebookPrefix);
            // $model->instagram_ref = $model->generateReference($model, $instagramPrefix);
            // $model->youtube_ref = $model->generateReference($model, $youtubePrefix);
        });
    }

}
