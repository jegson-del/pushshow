<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Requests\CampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\User;
use App\Jobs\smsJob;
use App\Jobs\CampaignSettlementJob;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;

class CampaignController extends Controller
{
    use ApiResponse;

    public function makeLive($campaign)
    {
        try {
            $model = Campaign::findOrFail($campaign);
            if ($model->live) 
                return $this->error("Error Processing campaign is live already", 400);
            $model->live = true;
            $model->save();

            // Send custom message to Verified users
            $users = User::where('verified', true)->pluck('phone')->toArray();
            $numbers = array_unique($users);
            $message = "Hurry, a new campaign is live.";
            
            // This job is sent to the default connection's default queue...
            $send = smsJob::dispatchSync($numbers, $message);

            return $this->success($model, 'Successfully pushed to live.');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function settlePushers($campaign, $subscriber)
    {
        try {
            $campaignData = Campaign::where('status', false)->where('id', $campaign)->firstOrFail();
            $pusers = CampaignParticipated::pluck('user_id')->toArray();
            CampaignSettlementJob::dispatchSync($pusers, $campaign);
            $campaignData->completed = true;
            $campaignData->save();
            return $this->success('successul');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function store(CampaignRequest $request)
    {
        try {
            $campaign = new Campaign();
            $campaign->unit = $request->unit;
            $campaign->amount = $request->amount;
            $campaign->facebook_post_link = $request->facebook_post_link;
            $campaign->instagram_post_link = $request->instagram_post_link;
            $campaign->youtube_post_link = $request->youtube_post_link;
            $campaign->save();
            return $this->success([$campaign]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $campaign)
    {
        try {
            Campaign::findOrFail($campaign);
            $response = Campaign::where('id', $campaign)->update([
                'amount' => $request->amount,
                'unit' => $request->amount,
                'facebook_post_link' => $request->facebook_post_link,
                'instagram_post_link' => $request->instagram_post_link,
                'youtube_post_link' => $request->youtube_post_link,
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function queryAll()
    {
        try {
            // dd("hi");
            $response = new CampaignResource(Campaign::all());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function querySingle($campaign)
    {
        try {
            // dd("hello");
            $response = new CampaignResource(Campaign::findOrFail($campaign));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destory($campaign)
    {
        try {
            $response = Campaign::findOrFail($campaign);
            $response->delete();
            return $this->success($response, 'Deleted successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
