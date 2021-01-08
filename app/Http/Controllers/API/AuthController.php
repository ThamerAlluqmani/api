<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {

        $rules = $request->validate([
            'email' => 'unique:users|required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'name' => 'required'
        ]);
        $rules['password'] = bcrypt($rules['password']);

        $user = User::create($rules);

        $accessToken = $user->createToken('authToken')->accessToken;

        return ['user' => $user, 'access_token' => $accessToken];

    }

    public function login(Request $request)
    {

        $rules = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($rules)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return ['user' => auth()->user() , 'access_token' => $accessToken];


    }
}
