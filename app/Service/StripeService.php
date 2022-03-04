<?php

namespace App\Service;
use Illuminate\Support\Facades\Config;
use Stripe;

class StripeService 
{
    public function payment($amount, $token)
    {
        Stripe\Stripe::setApiKey(Config::get('maps.stripe.stripe_secret'));
        Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $token,
                "description" => "This payment is tested purpose pushcrowds.com"
        ]);
    }
}