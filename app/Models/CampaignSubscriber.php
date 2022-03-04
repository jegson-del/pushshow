<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'subscriber_id'
    ];
}
