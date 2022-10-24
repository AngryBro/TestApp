<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function logout(Request $request) {
        $newToken = Str::random(80);
        $user = User::where('remember_token',$request->bearerToken())
        ->first();
        if($user === null) return response()->json([],403);
        $user->remember_token = $newToken;
        $user->save();
        return response()->json([]);
    }

    function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) return response()->json([],422);
        $data = $validator->validated();
        $user = User::where('login',$data['login'])
        ->first();
        if(($user!==null)&&password_verify($data['password'],$user->password_hash)) {
            return response()->json(['token' => $user->remember_token]);
        }
        return response()->json([],403);
    }

}
