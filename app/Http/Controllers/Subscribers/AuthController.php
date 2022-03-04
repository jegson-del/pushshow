<?php

namespace App\Http\Controllers\Subscribers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::guard('subscriber')->attempt($credentials)) {
                return $this->error('Invalid login', 401);
            }
            $subscriber = Auth::guard('subscriber')->user();
            $token = $subscriber->createToken('Authorization Token')->accessToken;

            return $this->success(['token_type' => 'Bearer', 'subscriber' => $subscriber, 'token' => $token ], 'Login successful');

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $this->formValidation($request);
            DB::beginTransaction();
            $data = [
                'email' => $request->email,
                'business_name' => $request->business_name,
                'password' => Hash::make($request->password),
                'referrer' => $request->referrer
            ];
           
            $subscriber = Subscriber::create($data);
            $token = $subscriber->createToken('Authorization Token')->accessToken;
            DB::commit();
            return $this->success(['token_type' => 'Bearer', 'subscriber' => $subscriber,  'access_token' => $token], 'Subscriber registration successful');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 500);
        }
    }

    protected function formValidation($request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
            'business_name' => 'required|string|max:255|unique:subscribers',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
            'referrer' => 'string'
        ]);
    }


}
