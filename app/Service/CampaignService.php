<?php

namespace App\Service;
use App\Helpers\TransactionLogger;

class CampaignService
{
    public function settlePushers($model,$oldBalance,$newBalance)
    {
        $log = new TransactionLogger($model,'005',$oldBalance,$newBalance,$model);
        $log->financialHandler();
    }
}