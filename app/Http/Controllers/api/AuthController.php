<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(Request $reques){

        $fields = Validator::make($reques->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($fields->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $fields->errors()
            ]);
        }

        $user = User::where('email', $reques->email)->first();

        // verify email exists
        if(!$user){
            return response()->json([
                'message' => 'User not found',
                'errors' => ['email' => 'Email not found']
            ], 404);
        }

        // verify password

        if(!password_verify($reques->password, $user->password)){
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

    public function destroy(Request $reques){
        $reques->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }

    public function show(Request $reques){
        return response()->json([
            'item' => $reques->user()
        ]);
    }
}
