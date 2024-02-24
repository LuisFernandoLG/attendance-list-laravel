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


class RegisterUserController extends Controller
{
    public function store(RegisterUserRequest $request, RegisterUserService $registerUserService){
        $user = $registerUserService->register($request->name, $request->email, $request->password);
        $registerUserService->sendEmail($user);
    
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Item created successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }
}
