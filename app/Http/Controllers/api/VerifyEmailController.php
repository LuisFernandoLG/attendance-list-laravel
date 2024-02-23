<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{
    public function store(Request $request){

        $fields = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string'
        ]);

        if($fields->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $fields->errors()
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'message' => 'User not found',
                'errors' => ['email' => 'Email not found']
            ], 404);
        }

        $validation = (new Otp())->validate($user->email, $request->code);
        if($validation->status){

            $user->markEmailAsVerified();
            return response()->json([
                'message' => 'User verified successfully'
            ]);
        }

        $isUserVerified = $user->hasVerifiedEmail();

        if($isUserVerified){
            return response()->json([
                'message' => 'User already verified'
            ]);
        }

        // messagge: OTP Expired,

        if($validation->message == 'OTP Expired'){
            return response()->json([
                'message' => 'OTP Expired',
                'errors' => ['code' => 'OTP Expired'],
            ], 400);
        }



        // responde 400
        return response()->json([
            'message' => 'Invalid code',
            'errors' => ['code' => 'Invalid code'],
            'validation' => $validation,

        ], 400);

    }
}
