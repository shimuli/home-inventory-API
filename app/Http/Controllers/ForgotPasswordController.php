<?php

namespace App\Http\Controllers;

use App\Mail\forgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function code(Request $request)
    {
        $rules = [

            'email' => 'required|email',
        ];

        $this->validate($request, $rules);

        $user = User::where('email', $request->email)->first();

        //check if user is verified
        if (!$user->isVerified()) {
            return response()->json(['unverified' => 'Please verify your account first'], 401);
        }
        $user->verify_code = User::generateCheckCode();

        $user->save();
        // return response()->json(['code' => $user->verify_code], 200);

        retry(5, function () use ($user) {
            //sed email method use in production
            Mail::to($user)->send(new forgotPasswordMail($user));
        }, 100);

        return response()->json(['verify_code' => $user->verify_code], 200);
    }

    public function resend_code(User $user)
    {
        // retry after every 10 seconds five times before failing
            //sed email method use in production
            Mail::to($user)->send(new forgotPasswordMail($user));

        return $this->showMessage('The verification code was resend');

    }
}
