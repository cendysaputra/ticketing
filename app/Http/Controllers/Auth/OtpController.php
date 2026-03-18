<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.otp-email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->input('email');

        Otp::where('email', $email)->delete();

        $code = rand(100000, 999999);

        Otp::create([
            'email'      => $email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("Kode OTP: $code (berlaku 10 menit)", function ($message) use ($email) {
            $message->to($email)->subject('Kode OTP Login');
        });

        return redirect()->route('otp.verify.form')->with('email', $email);
    }

    public function showVerifyForm()
    {
        return view('auth.otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);

        $otp = Otp::where('email', $request->input('email'))
                  ->where('code', $request->input('code'))
                  ->where('expires_at', '>', now())
                  ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'Kode OTP salah atau sudah expired.']);
        }

         $user = User::firstOrCreate(
            ['email' => $request->input('email')],
            [
               'name'     => explode('@', $request->input('email'))[0],
               'password' => bcrypt(str()->random(32)),
            ]
);

        $otp->delete();

        Auth::login($user);

        return redirect()->intended('/admin');
    }
}