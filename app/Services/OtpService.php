<?php

namespace App\Services;

use App\Helpers\Helpers;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;

class OtpService
{
    static function isMobileValid($number)
    {
        return !is_null(User::query()->where('mobile', $number)->first());
    }

    static function generateOtp($mobile)
    {
        $mobile = Helpers::mobileNumberNormalize($mobile);
        $user = User::query()->where('mobile', $mobile)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = Otp::query()->where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }


        // Create a New OTP
        return Otp::query()->create([
            'user_id' => $user->id,
            'code' => rand(103246, 999999),
            'expired_at' => Carbon::now()->addMinutes(1)
        ]);
    }

    static function verifyOtp($mobile,$code)
    {
        $mobile = Helpers::mobileNumberNormalize($mobile);
        $user = User::query()->where('mobile',$mobile)->first();
        $otpGeneratedCode = Otp::query()
            ->where('user_id',$user->id)
            ->where('expired_at','>',now());
        if(!$otpGeneratedCode->exists()){
            return false;
        } else {
            if($otpGeneratedCode->first()->code==$code){
                $otpGeneratedCode->delete();
                $user->verifyMobile();
                return true;
            }
        }
        return false;
    }

}
