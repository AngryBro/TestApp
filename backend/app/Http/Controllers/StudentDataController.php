<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class StudentDataController extends Controller
{
    function loginUpdate(Request $request) {
        $token = $request->bearerToken();
        $validator = Validator::make($request->all(),[
            'login' => 'required|min:3',
        ]);
        if($validator->fails()) return response()->json([
            'message' => 'invalid login'
        ],422);
        $login = $validator->validated()['login'];
        if(User::firstWhere('login',$login)!==null) {
            return response()->json([
                'message' => "login $login already taken"
            ],400);
        }
        $user = User::firstWhere('remember_token',$token);
        $user->login = $login;
        $user->save();
        return response()->json([]);
    }
}
