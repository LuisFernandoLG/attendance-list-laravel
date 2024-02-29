<?php

namespace App\Http\Services;

use App\Models\User;
use Ichtrojan\Otp\Otp;

class RegisterUserService
{

    public function register($name, $email, $password, $timezone): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'timezone' => $timezone
        ]);

        return $user;
    }

    public function isEmailAvailable($email):bool{
        $available = User::where('email', $email)->first() ? false : true;
        return $available;
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
            throw new \Exception($validation->message);
        }
        
        $user->markEmailAsVerified();
        return 'Email verified';
    }

    public function resendEmail($email): string
    {
        $user = User::where('email', $email)->first();

        if($user->hasVerifiedEmail()) return 'Email has been already verified';
        
        $this->sendEmail($user);
        return 'Email sent successfully';
    }
}
