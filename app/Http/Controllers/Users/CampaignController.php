<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignParticipated;
use App\Models\CampaignSubscriber;

class CampaignController extends Controller
{
    use ApiResponse;

    public function onlineCampaigns()
    {
        try {
            $response = Campaign::where('status', true)->where('live', true)->get();
            return $this->success($response, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function userCampaigns($user)
    {
        try {
            $response = CampaignParticipated::where('user_id', $user)->get();
            return $this->success($response, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function targetCampaign(Request $request, $reference)
    {
        try {
            $query = substr($reference, 0, 4);
            $model;
            switch ($query) {
                case 'pcfb':
                    $model = Campaign::where('facebook_reference', $reference)->first();
                    $model = $model->only(['id', 'reference', 'facebook_reference', 'facebook_post_link']);
                    break;
                case 'pcin':
                    $model = Campaign::where('instagram_reference', $reference)->first();
                    $model = $model->only(['id', 'reference', 'instagram_reference', 'instagram_post_link']);
                    break;
                case 'pcyt':
                    $model = Campaign::where('youtube_reference', $reference)->first();
                    $model = $model->only(['id', 'reference', 'youtube_reference', 'youtube_post_link']);
                    break;
                default:
                    break;
            }
            return $this->success($model, 'successful'); 
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function confirmTargetLink($user, $campaign, $reference)
    {
        try {
            $query = substr($reference, 0, 4);
            $subscriber = CampaignSubscriber::where('campaign_id', $campaign)->firstOrFail();
            $model = CampaignParticipated::where('campaign_id', $campaign)->where('user_id', $user)->first();
            if ($model) {
                $model->facebook = ($query === 'pcfb' ? true : $model->facebook) ? true : false;
                $model->instagram = ($query === 'pcin' ? true : $model->instagram) ? true : false;
                $model->youtube = ($query === 'pcyt' ? true : $model->youtube) ? true : false;
                $model->save();
            } else {
                $model = CampaignParticipated::create([
                    'campaign_id' => $campaign,
                    'user_id' => $user,
                    'subscriber_id' => $subscriber->subscriber_id,
                    'facebook' => $query === 'pcfb' ? true : false,
                    'instagram' => $query === 'pcin' ? true : false,
                    'youtube' => $query === 'pcyt' ? true : false,
                    'status' => true
                ]);
            }
            return $this->success($model, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function closeCampaign($campaign, $user, $subscriber)
    {
        try {
            CampaignSubscriber::where('campaign_id', $campaign)
                ->where('subscriber_id', $subscriber)->firstOrFail();

            $campaignData = Campaign::where('status', true)->where('id', $campaign)->firstOrFail();
            $userData = User::findOrFail($user);

            $participatedUsers = CampaignParticipated::where('campaign_id',$campaign)
                ->where('subscriber_id', $subscriber)->get();
            
            // $hasPaticipated = CampaignParticipated::where('campaign_id', $campaign)
            //     ->where('subscriber_id', $subscriber)->where('user_id', $user)->first();

            // if ($hasParticipated) {
            //     return $this->error('You have already paticipated', 400);
            // }

            if (intval($campaignData->unit) === count($participatedUsers)) {
                return $this->error('Campaign has reached expected number of pushers', 400);
            }

            $participatedCount = CampaignParticipated::where('campaign_id',$campaign)
                        ->where('subscriber_id', $subscriber)->get();

            if (intval($campaignData->unit) === count($participatedCount)) {
                // Send email messages has ended
                $campaignData->status = false;
                $campaignData->save();
            }
            
            return $this->success(['participantes' => $participatedCount], 'successful');

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
