<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_role'=>$request->user_role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Mail::to($user->email)->send(new EmailVerification($user->email));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}