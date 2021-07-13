<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function reset_password(Request $request)
    {

        $rules = [

            'email' => 'required|email',
            'verify_code' => 'required|digits:4',
            'password' => 'required|min:6',
        ];

        $this->validate($request, $rules);

        $user = User::where('email', $request->email)->first();

// check if user is verified before log in

        if ($user->verify_code != $request->verify_code) {

            return response()->json(['Invalid' => 'The code is invalid'], 401);
        }

        $user->password = bcrypt($request->password);
        $user->verify_code = null;
        $user->save();
        return response()->json(['Success' => 'Awesome'], 200);

    }
}
