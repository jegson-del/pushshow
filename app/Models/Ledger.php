<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RefGenerator;

class Ledger extends Model
{
    use HasFactory, RefGenerator;

    protected $refPrefix = 'PLT-';

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->reference = $model->generateReference();
        });
    }

    protected $fillable=[
        'reference',
        'user_id',
        'user_type',
        'oldBalance',
        'newBalance',
        'description',
        'entity',
        'entity_id',
        'type',
        'code',
        'amount'
    ];

    public function getRouteKeyName()
    {
        return 'reference';
    }

}
