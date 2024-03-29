<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Services\RegisterUserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    public function store(VerifyEmailRequest $request, RegisterUserService $registerUserService){      
        $user = User::where('email', $request->email)->first();
        if(!$user) return response()->json([
            'message' => 'The email does not belong to any user',
            'errors' => ['email'=>['The email does not belong to any user']]
        ], Response::HTTP_NOT_FOUND);
        
        try {
            $result = $registerUserService->verifyEmailOtp($request->email, $request->code);
            return response()->json([
                'message' => $result
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function show(ResendEmailVerificationRequest $request, RegisterUserService $registerUserService){
        $emailExist = User::where('email', $request->email)->first();
        if(!$emailExist) return response()->json([
            'message'=> 'The email does not belong to any user',
            'errors'=> ['email' => ['The email does not belong to any user']]
        ]);
        
        $result = $registerUserService->resendEmail($request->email);

        return response()->json([
            'message' => $result
        ]);
    }
}
