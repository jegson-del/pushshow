<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawalResource;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Withdrawal;
use App\Models\User;

class UserWithdrawalController extends Controller
{
    use ApiResponse;

    public function create(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'numeric|required',
                'paymentMethod' => 'string|required',
                'user_id' => 'required'
            ]);

            // Check if user has at list $10
            $user = User::findOrFail($request->user_id);
            if ($user->wallet_balance < 10) 
                return $this->error('Wallet balance is less than $10.', 400);
                
            if ($request->amount > $user->wallet_balance)
                return $this->error('Request amount is greater than wallet balance.', 400);

            $response = Withdrawal::create([
                'amount' => $request->amount,
                'phone' => $request->phone,
                'address' => $request->address,
                'accountName' => $request->accountName,
                'accountNo' => $request->accountNo,
                'bankName' => $request->bankName,
                'sortCode' => $request->sortCode,
                'paymentMethod' => $request->paymentMethod,
                'user_id' => $request->user_id
            ]);
            return $this->success($response, 'successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function show($user)
    {
        try {
            $response = new WithdrawalResource(Withdrawal::where('user_id', $user)->get());
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destory($withdrawal)
    {
        try {
            $response = Withdrawal::findOrFail($withdrawal);
            if ($response->status !== "pending") return $this->error('Can no longer be deleted', 400);
            $response->delete();
            return $this->success($response, 'Deleted successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
