<?php

namespace App\Service;
use App\Models\User;
use App\Models\Subscriber;
use App\Helpers\TransactionLogger;

class ReferrerService
{
    protected $credit;

    public function bonus($referrer, $user)
    {
        /**
         * @param $user specify user referred.
         *
         * 
        */

        $pusher =  User::where('reference', $referrer)->first();
    
        if ($referrer) {
            /* This line check amount to sned to the referrer. 
             Referrer will get 10 if the user is pusher and 100 point for subscriber 
             **/
            $model = get_class($user) === 'App\Models\User' ? $this->credit = 10 : $this->credit = 100; 
            /* Add bonus to referrer **/
            if ($pusher) {
                $oldBonus = $pusher->bonus;
                $pusher->bonus = $oldBonus + $this->credit;

                $oldBalance = $pusher->wallet_balance;
                $newBalance = $oldBalance;
                $log = new TransactionLogger($pusher,'006',$this->credit,$oldBalance,$newBalance,$pusher);
                $log->financialHandler();
            }else {
                $subscriber = Subscriber::where('reference', $referrer)->first();
                if ($subscriber) {
                    $oldBonus = $subscriber->bonus;
                    $subscriber->bonus = $oldBonus + $this->credit;
    
                    $oldBalance = $subscriber->wallet_balance;
                    $newBalance = $oldBalance;
                    $log = new TransactionLogger($subscriber,'006',$this->credit,$oldBalance,$newBalance,$subscriber);
                    $log->financialHandler();
                }
            }
        }
    }
}