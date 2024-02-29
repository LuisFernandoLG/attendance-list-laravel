<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'message'=> "The email does not belong to any user",
                'errors' => ['email'=>['Email not registered']]
            ]);
        }

        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password',
                'errors' => ['password' => 'Invalid password']
            ], 400);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }

    public function show(Request $reques)
    {
        return response()->json([
            'message' => 'Item retrieved successfully',
            'item' => $reques->user()
        ]);
    }
}
