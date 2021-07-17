<?php

namespace App\Http\Controllers\User;

use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        //$this->middleware('auth:api')->except(['store']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);

        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::all();
        return $this->returnAll($user, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10|unique:users',
            'password' => 'required|min:6',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        //$data['verify_code'] = User::generateCheckCode();
        $users = User::create($data);

        $this->sendSMS($users); // used to verify phone number
        return $this->returnOne($users, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->returnOne($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'phone|digits:10|unique:users,phone,' . $user->id,
            'password' => 'min:6', // confirmed
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // confirm email if updated
        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users are allowed to modify admin field', 409);
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }
        $user->save();
        return response()->json(['data' => $user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            "message" => 'Deleted Successfully', 'code' => '204',
        ], 200);

    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage("The account has been verified");
    }

    // resend verification email
    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse("This user is already verified", 409);
        }

        // retry after every 10 seconds five times before failing
        retry(5, function () use ($user) {
            //sed email method use in production
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email was resend');

    }

    public function sendSMS(User $user)
    {
        $username = env('SMS_ACCESS_KEY_ID'); // use 'sandbox' for development in the test environment
        $apiKey = env('SMS_SECRET_ACCESS_KEY'); // use your sandbox app API key for development in the test environment
        $AT = new AfricasTalking($username, $apiKey);
        $sms = $AT->sms();

        retry(5, function () use ($user, $sms) {
            $result = $sms->send([
                'to' => $user->phone,
                'message' => 'Shopping buddy: Hello ' . $user->name . ' verify your phone number using this code: ' . $user->verify_code,
            ]);

        });

    }

    public function userProfile()
    {

        return Auth::user();
    }

}
