<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\User\SignupRequest;
use App\Http\Requests\User\SigninRequest;
use App\Http\Resources\User\UserResource;


class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json([
            "message" => "User created successfully",
            'user' => new UserResource($user)
        ],201);
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
}
