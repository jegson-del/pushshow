<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BadgeRequest;
use App\Http\Resources\BadgesResource;
use App\Traits\ApiResponse;
use App\Models\BadgePositive;
use App\Helpers\TransactionLogger;
use App\Models\User;

class PositiveBadgeController extends Controller
{
    use ApiResponse;

    public function store(BadgeRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'bonus' => $request->bonus,
            ];
            $response = BadgePositive::create($data);
            return $this->success($response, 'successfully created badge');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function update(BadgeRequest $request, $badge)
    {
        try {
            BadgePositive::findOrFail($badge);
            $response = BadgePositive::where('id', $badge)->update([
                'name' => $request->name,
                'description' => $request->description,
                'bonus' => $request->bonus,
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function queryAll()
    {
        try {
            $response = new BadgesResource(BadgePositive::all());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function querySingle($badge)
    {
        try {
            $response = new BadgesResource(BadgePositive::findOrFail($badge));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function assignUserBadge($badge, $user)
    {
        try {
            $userData = User::findOrFail($user);
            $badgeData = BadgePositive::findOrFail($badge);
            $userData->badgePositive_id = $badge;
            
            $oldBalance = $userData->wallet_balance;
            $newBalance = $oldBalance + $badgeData->bonus; //

            $log = new TransactionLogger($userData,'004',$oldBalance,$newBalance,$userData);
            $log->financialHandler();

            return $this->success($userData, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function destory($badge)
    {
        try {
            $response = BadgePositive::findOrFail($badge);
            $response->delete();
            return $this->success($response, 'Deleted successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
