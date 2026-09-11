<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\User\SignupRequest;
use App\Http\Requests\User\SigninRequest;
use App\Http\Requests\User\SendVerificationEmailRequest;
use App\Http\Requests\User\SendResetPasswordEmailRequest;
use App\Http\Requests\User\SetNewPasswordRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Password;




class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $user->sendEmailVerificationNotification($request->callback_url);

        return response()->json([
            "message" => "User created successfully",
            'user' => new UserResource($user)
        ], 201);
    }

    public function signin(SigninRequest $request)
    {
    
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Invalid credentials',
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "message" => "Login successful",
            "token" => $token,
            "user" => new UserResource($user)
        ],200);
    }

    public function signout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "Logout successful"
        ],200);
    }

    public function verify(Request $request)
    {
        return response()->json([
            "user" => new UserResource($request->user()),
            "message" => "User verified successfully"
        ],200);
    }
    function sendVerificationEmail(SendVerificationEmailRequest $request){
        $user = User::where('email', $request->email)->first();
        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'User already verified',
            ]);
        }
        $user->sendEmailVerificationNotification($request->callback_url);
        return response()->json([
            "message" => "Email verification sent successfully"
        ],200);
    }
    function verifyEmail(Request $request){
        $user = User::findOrFail($request->route('id'));
        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'User already verified',
            ]);
        }
        $user->markEmailAsVerified();
        return response()->json([
            "message" => "Email verified successfully"
        ],200);
    }
    function sendResetPasswordEmail(SendResetPasswordEmailRequest $request)
    {
        $status = Password::sendResetLink(
            ['email' => $request->email],
            function ($user, $token) use ($request) {
                $user->sendPasswordResetNotification($token, $request->callback_url);
            }
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response([
                'message' => 'Password reset link sent to your email'
            ], 200);
        }

        return response([
            'message' => 'Password reset link sent to your email'
        ], 200);
    }

    function setNewPassword(SetNewPasswordRequest $request)
    {
        $status = Password::reset(
            [
                'token' => $request->token,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ],
            function ($user, $password) {
                $user->password = $password;
                $user->save();
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'password' => [__($status)],
            ]);
        }

        return response([
            'message' => 'Password has been reset successfully.'
        ], 200);
    }

}
