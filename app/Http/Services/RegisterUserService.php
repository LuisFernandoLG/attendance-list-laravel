<?php

namespace App\Http\Services;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class RegisterUserService
{

    public function register($name, $email, $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        return $user;
    }

    public function sendEmail(User $user): void
    {
        $code = (new Otp)->generate($user->email, 'numeric', 6, 15);

        $user->sendOtpCodeToEmail($code->token);
    }

    public function verifyEmailOtp($email, $code): string
    {
        $user = User::where('email', $email)->first();
        if ($user->hasVerifiedEmail()) return 'Email has been already verified';

        $validation = (new Otp())->validate($email, $code);

        if (!$validation->status) {
            $user->markEmailAsVerified();
            throw new \Exception($validation->message);
        }

        return 'Email verified';
    }

    public function resendEmail($email): string
    {
        $user = User::where('email', $email)->first();
        if(!$user->hasVerifiedEmail()) return 'Email has been already verified';
        
        $this->sendEmail($user);
        return 'Email sent successfully';
    }
}
