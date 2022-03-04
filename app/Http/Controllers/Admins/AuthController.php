<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        try {    
            $credentials = $request->only(['email', 'password']);
          
            if (!Auth::guard('admin')->attempt($credentials)) {
                return $this->error('Invalid login', 401);
            }
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('Authorization Token')->accessToken;
            
            return $this->success(['token_type' => 'Bearer', 'user' => $admin,  'token' => $token ], 'Login successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $this->formValidation($request);

            $data = [
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ];

            $admin = Admin::create($data);
            $token = $admin->createToken('Authorization Token')->accessToken;

            return $this->success(['token_type' => 'Bearer', 'admin' => $admin, 'token' => $token ], 'Admin registeration successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    protected function formValidation($request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password'
        ]);
    }
}
