<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAccountByEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ichtrojan\Otp\Otp;


class RegisterUserController extends Controller
{
    public function store(Request $request){
        // validation
        $fields = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // verify if email already exists
        $user = User::where('email', $request->email)->first();

        if($user){
            return response()->json([
                'message' => 'Email already exists',
                'errors' => ['email' => 'Email already exists']
            ]);
        }

        if($fields->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $fields->errors()
            ]);
        }

        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        
        // Mail::to($user)->send(new VerifyAccountByEmail($user));
        $code = (new Otp)->generate($user->email, 'numeric', 6, 15);

        $user->sendOtpCodeToEmail($code->token);
        $token = $user->createToken('api-token')->plainTextToken;

        // return user
        return response()->json([
            'message' => 'Item created successfully',
            'user' => $user,
            'token' => $token
        ]);
    }
}
