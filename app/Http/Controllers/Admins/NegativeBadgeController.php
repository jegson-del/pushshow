<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BadgeRequest;
use App\Http\Resources\BadgesResource;
use App\Traits\ApiResponse;
use App\Models\BadgeNegative;
use App\Models\User;

class NegativeBadgeController extends Controller
{
    use ApiResponse;

    public function store(BadgeRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description
            ];
            $response = BadgeNegative::create($data);
            return $this->success($response, 'successfully created badge');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function update(BadgeRequest $request, $badge)
    {
        try {
            BadgeNegative::findOrFail($badge);
            $response = BadgeNegative::where('id', $badge)->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function queryAll()
    {
        try {
            $response = new BadgesResource(BadgeNegative::all());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function querySingle($badge)
    {
        try {
            $response = new BadgesResource(BadgeNegative::findOrFail($badge));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function assignUserBadge($badge, $user)
    {
        try {
            $userData = User::findOrFail($user);
            $badgeData = BadgeNegative::findOrFail($badge);
            $userData->badgeNegative_id = $badge;
           
            if ($badgeData->name === ' Red') {
                $userData->disabled = true;
            }
            $userData->save();
            // Send Email Message
            return $this->success($userData, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
    public function destory($badge)
    {
        try {
            $response = BadgeNegative::findOrFail($badge);
            $response->delete();
            return $this->success($response, 'Deleted successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
