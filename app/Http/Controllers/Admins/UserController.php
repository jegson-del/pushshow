<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use App\Service\ReferrerService;
use App\Models\User;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(ReferrerService $referrer) 
    {
        $this->referrerService = $referrer;
    }

    public function queryAll()
    {
        try {
            $response = new UserResource(User::all());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function querySingle($user)
    {
        try {
            $response = new UserResource(User::find($user));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function verifyUser($user)
    {
        try {
            $model = User::findOrFail($user);
            if ($model->verified) {
                return $this->error('User has been verified', 400);
            }
            $model->verified = true;
            /* Referreral earnings code **/
            $this->referrerService->bonus($model->referrer, $model);
            $model->save();
            return $this->success('sucessful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

}
