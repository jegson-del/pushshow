<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\WithdrawalResource;
use App\Traits\ApiResponse;
use App\Models\Withdrawal;

class WithdrawalController extends Controller
{
    use ApiResponse;

    public function queryAll()
    {
        try {
            $response = new WithdrawalResource(Withdrawal::all());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function approve($withdrawal)
    {
        try {
            $model = Withdrawal::findOrFail($withdrawal);
            $model->status = 'approved';
            $model->save();
            // Send email message to user
            return $this->success($model, 'successfully approved');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function reject($withdrawal)
    {
        try {
            $model = Withdrawal::findOrFail($withdrawal);
            $model->status = 'rejected';
            $model->save();
            // Send email message to user
            return $this->success($model, 'successfully rejected');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
