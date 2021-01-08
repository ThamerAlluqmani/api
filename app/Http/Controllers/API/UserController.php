<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    //
    public function updatePassword(Request $request)
    {

        $user = auth()->user();
        if (!Hash::check($request->password, $user->password)) {

            return response()->json([
                'message' => 'Your password current password is invalid'
            ], 401);
        }

        $rules = $request->validate([
            'password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required'
        ]);

        $user->password = bcrypt($rules['new_password']);
       if ( $user->save() ){
           return ['message' => 'Password updated successfully'];
       };
        return response()->json(['message' => 'There is an error , please try again later'] , 500);


    }
}
