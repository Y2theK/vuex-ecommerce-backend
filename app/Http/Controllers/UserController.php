<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

   
    public function store(UserRequest $request)
    {
        $validated_data = $request->validated();
        //hash the password
        $validated_data['password'] = Hash::make($validated_data['password']);
        $user = User::create(
            $validated_data
        );

        return new UserResource($user);
    }

    
    public function show(User $user)
    {
        return new UserResource($user);
    }

   
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        // $user = User::findOrFail($user->id);
        return new UserResource($user);
    }

    
    public function destroy(User $user)
    {
        $user->carts()->delete();  //delete user's cart
        $user->delete();
        return response()->json([
            "message" => "User deleted successfully",
        ], 200);
    }
}
