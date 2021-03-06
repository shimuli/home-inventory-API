<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $rules = [

            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $this->validate($request, $rules);

        $user = User::where('email', $request->email)->first();

        // check if user is verified before log in

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json(['Invalid' => 'Password or email is incorrect'], 401);

        }

        if (!$user->isVerified()) {
            return response()->json(['unverified' => 'Please verify your account first'], 401);
        }

        // return response()->json(['data' => $users], 201);

        // return  ( $user->createToken('Auth Token')->accessToken);

        return response()->json(
            [
                'message' => "Success",
                'token' => $user->createToken('Auth Token')->accessToken,
                'data' => $user,

            ], 200);
    }

    
}
