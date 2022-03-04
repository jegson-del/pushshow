<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\SessionGuard;
use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::guard('web')->attempt($credentials)) {
                return $this->error('Invalid login', 401);
            }
            $user = Auth::guard('web')->user();
            
            if ($user->disabled) {
                return $this->error('Your account has been block', 403);
            }
            
            $token = $user->createToken('Authorization Token')->accessToken;
            
            return $this->success(['token_type' => 'Bearer', 'user' => $user, 'access_token' => $token], 'Login successful');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'referrer' => $request->referrer
            ];

            $user = User::create($data);
            $user->sendEmailVerificationNotification();
            $token = $user->createToken('Authorization Token')->accessToken;
            DB::commit();
            return $this->success(['token_type' => 'Bearer', 'user' => $user, 'access_token' => $token], 'User registeration successful');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $status = Password::sendResetLink(
                $request->only('email')
            );
            if ($status === Password::RESET_LINK_SENT){
                return $this->success(['status' => __($status)], 'Password reset link sent');
            }
            return $this->error(trans($status), 400);    
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->success('Password reset successfully');
        }

        return $this->error(['message'=> __($status), 500]);
    }
}
