<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'amount',
        'paymentMethod',
        'address',
        'phone',
        'status',
        'accountName',
        'accountNo',
        'bankName',
        'sortCode'
    ];
}
