<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Services\RegisterUserService;
use App\Mail\VerifyAccountByEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Response;

class RegisterUserController extends Controller
{
    public function store(RegisterUserRequest $request, RegisterUserService $registerUserService){
        if(!$registerUserService->isEmailAvailable($request->email)) return response()->json([
            'message' => 'Email has been already taken',
            'errors' => ['email' => ['email is alreadt taken']]
        ]);
        
        $user = $registerUserService->register($request->name, $request->email, $request->password, $request->timezone);
        $registerUserService->sendEmail($user);
    
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Item created successfully',
            'user' => $user,
            'token' => $token,
        ], Response::HTTP_CREATED);
    }
}
