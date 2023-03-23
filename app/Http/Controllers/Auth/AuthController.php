<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['password'] = bcrypt($validated_data['password']); //using bcrypt method instead of Hash::make
        $user = User::create($validated_data);
       
        $token = $user->createToken('apiToken')->plainTextToken;
        // return $token;
        return response()->json([
             'user' => new UserResource($user),
             'token' => $token
        
        ], 200);
    }
    public function login(Request $request)
    {
        $validated_data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $validated_data['email'])->first();
        if (!$user || !Hash::check($validated_data['password'], $user->password)) {
            return response([
                'message' => 'Invalid Credential'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        // Auth::user()->tokens()->delete();
        $request->user()->tokens()->delete();
        return [
            'message' => 'User logged out'
        ];
    }
}
