<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminForgotPasswordController extends Controller
{
    use ApiResponse;

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $user = Admin::where('email', $request->email)->first();
            if (!$user) return $this->error('We can not find a user with that email address.', 400);
            
            $token = Str::random(60);
            $user->password_token = $token;
            $user->save();
            $user->sendPasswordResetNotification($token);
            return $this->success(['status' => 'We have emailed your password reset link!'], 'Password reset link sent');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function passwordReset(Request $request)
    {
      try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', RulesPassword::defaults()],
            ]);

            $user = $user = Admin::where([
                'email' => $request->email,
                'password_token' => $request->token
                ])->first();

            if (!$user) return $this->error('Invalid token', 400);
            
            $user->password = Hash::make($request->password);
            $user->password_token = null;
            $user->remember_token = Str::random(60);
            $user->save();

            return $this->success('Password reset successfully');
      } catch (\Throwable $th) {
          return $this->error($th->getMessage(), 500);
      }
    }
}
