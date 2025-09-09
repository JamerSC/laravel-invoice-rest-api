<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // validate register fields
        $fields = $request->validated();

        // create to register new user
        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // create access token
        $token = $user->createToken('accessToken')->plainTextToken;

        // request response
        return response()->json([
            'message' => 'User registered successfully!',
            'user'    => $user,
            'token'   => $token,
        ], 201); // Created 201
    }

    public function login(LoginRequest $request)
    {
        // validate login fields
        $fields = $request->validated();

        // find the user email first
        $user = User::where('email', $fields['email'])->first();

        // check if the password is correct
        if (!$user || !Hash::check($fields['password'], $user->password))
        {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401); // Unauthorized 401   
        }

        // create access token for login user
        $token = $user->createToken('accessToken')->plainTextToken;

        // request response
        return response()->json([
            'message' => 'Login successfully!',
            'user'    => $user,
            'token'   => $token,
        ], 200); // Ok! 200
    }

    public function logout(Request $request)
    {
        // delete user token
        $request->user()->tokens()->delete();

        // request response
        return response()->json([
            'message' => 'Logout succeessfully!'
        ], 200); // Ok! 200
    }
}
