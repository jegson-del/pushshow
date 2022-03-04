<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Service\CampaignService;
use App\Models\User;
use App\Models\Campaign;

class campaignSettlementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pushers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pushers, $campaign)
    {
        $this->pusers = $pushers;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($pushers as $puser) {
            $user = User::find($puser);
            $campaignData = Campaign::find($campaign);

            $percentage = 25/100; // 25 percentage off amount
            $campaign_amount = $campaignData->amount;
            $perPrice = $campaign_amount * $percentage;
            $reward = $campaign_amount - $perPrice / $campaignData->unit;
            $oldBalance = $user->wallet_balance;  
            $newBalance = $oldBalance + $reward;

            // Send email notification here
            CampaignService::settlePushers($user,$oldBalance,$newBalance);
        }
    }
}
