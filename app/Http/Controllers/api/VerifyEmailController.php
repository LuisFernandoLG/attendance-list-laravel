<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Services\RegisterUserService;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function store(VerifyEmailRequest $request, RegisterUserService $registerUserService){      
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
        $result = $registerUserService->resendEmail($request->email);

        return response()->json([
            'message' => $result
        ]);
    }
}
