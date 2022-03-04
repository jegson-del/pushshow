<?php

namespace App\Http\Controllers\Subscribers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriberResource;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\SubscriberRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service\ImageService;
use App\Service\StripeService;
use App\Service\ReferrerService;
use App\Traits\ApiResponse;
use App\Models\Campaign;
use App\Models\Subscriber;
use App\Models\CampaignSubscriber;
use App\Helpers\TransactionLogger;


class SubscriberController extends Controller
{
    use ApiResponse;

    public function __construct(ImageService $image, StripeService $stripe, ReferrerService $referrer) 
    {
        $this->imageService = $image;
        $this->stripeService = $stripe;
        $this->referrerService = $referrer;
    }

    public function buyCampaign(Request $request, $subscriber)
    {
        try {

            DB::beginTransaction();

            $campaignData = Campaign::create([
                "amount" => $request->amount,
                "unit" => $request->unit,
                "facebook_reference" => $request->facebook_reference,
                "instagram_reference" => $request->instagram_reference,
                "youtube_reference" => $request->youtube_reference,
                'facebook_post_link' => $request->facebook_post_link,
                'instagram_post_link' => $request->instagram_post_link,
                'youtube_post_link' => $request->youtube_post_link,
            ]);

            $subscriberUser = Subscriber::findOrFail($subscriber);
            
            $this->stripeService->payment($campaignData->amount,$request->token);
  
            $order = CampaignSubscriber::create([
                'campaign_id' => $campaignData->id, 
                'subscriber_id' => $subscriber]);

            /* Transaction logger **/
            $oldBalance = $subscriberUser->wallet_balance;
            $newBalance = $oldBalance;
            $log = new TransactionLogger($subscriberUser,'001',$campaignData->amount,$oldBalance,$newBalance,$subscriberUser);
            $log->financialHandler();
            
            /* Referreral earnings code **/
            $this->referrerService->bonus($subscriberUser->referrer, $subscriberUser);

            DB::commit();
            return $this->success($order);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 500);
        }
    }

    public function updateCampaign(Request $request, $campaign)
    {
        try {
            Campaign::findOrFail($campaign);
            $response = Campaign::where('id', $campaign)->update([
                'facebook_post_link' => $request->facebook_post_link,
                'instagram_post_link' => $request->instagram_post_link,
                'youtube_post_link' => $request->youtube_post_link,
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function profileUpload(ImageRequest $request, $subscriber)
    {
        try {
            $response = Subscriber::findOrFail($subscriber);
            $imageUri = $this->imageService->imageUpload($request->image, $path = 'profile/');
            Subscriber::where('id',$subscriber)->update(['logo' => $imageUri]);         
            return $this->success('profile updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function getSubscriber($subscriber)
    {
        try {
            $response = new SubscriberResource(Subscriber::findOrFail($subscriber));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(SubscriberRequest $request, $subscriber)
    {
        try {
            Subscriber::findOrfail($subscriber);
            $response = Subscriber::where('id', $subscriber)->update([
                'description' => $request->description,
                'country' => $request->country,
                'phone' => $request->phone,
                'facebook_username' => $request->username,
                'facebook_link' => $request->facebook_link,
                'instagram_username' => $request->instagram_username,
                'instagram_link' => $request->instagram_link,
                'youtube_link' => $request->youtube_link,
                'youtube_username' => $request->youtube_username,
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

}
